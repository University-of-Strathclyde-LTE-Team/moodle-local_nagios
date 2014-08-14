# local_nagios

This is a Moodle plugin to simplify monitoring the Moodle service (as opposed to the hardware and infrastructure)
using [Nagios](http://www.nagios.org/). It provides:

1. A simple API which allows plugins to implement "services" which can be monitored.
2. A Nagios plugin script (/script/check_moodle) which communicates with the Moodle plugin
   to get service status into Nagios.
3. A couple of simple example services.

*Note that this plugin is intended for experienced systems administrators who are familiar with both Moodle and Nagios configuration!*

**NB: this is currently in development and should not be used on live systems!**

## Installation

### Moodle plugin
The Moodle plugin should be installed as normal.
### Nagios plugin
(All paths given are the default locations for a Ubuntu install of nagios3 using apt-get)
Copy / move the Nagios plugin (/script/check_moodle) to your Nagios plugins directory (/usr/lib/nagios/plugins). Change the permissions on the script to make it executable. Edit the $MOODLE_HOME and $PHP_COMMAND variables to reflect your environment.

## Configuration
1. Create a new file, moodle.cfg, in your Nagios plugins configuration directory (/etc/nagios-plugins/config).
2. Add a command definition for each Moodle service to be monitored, e.g.:

```
define command{
        command_name    check_moodle_cron
        command_line    /usr/lib/nagios/plugins/check_moodle -p local_nagios -s cron
        }
```
Use these command in Nagios service definitions as normal.

## Permission requirements

1. The Nagios plugin calls the script /cli/check.php in the Moodle plugin. This means that the user account Nagios is running under *must* have write access to the Moodle data directory, otherwise all checks will result in an error message.
2. If the user account Nagios is running under has to use sudo to call the PHP executable, this must be configured in the $PHP_COMMAND variable in the check_moodle script. The sudo access for the account must also be configured to be able to run the PHP executable without being challenged for a password.

## Service API

The local_nagios plugin scans other Moodle plugins looking for the callbacks "nagios_services" and "nagios_status" in lib.php.

### nagios_services callback

This provides metadata on the services provided by the plugin. It is a simple array of items like this:

```
array(
        'cron' => array(
            'name' => 'Cron job',
            'description' => 'Checks that the cron job is running properly by checking the last time it was run.',
            'variable' => 'Number of seconds since last run'
        ),
        'eventqueue' => array(
            'name' => 'Event queue',
            'description' => 'Monitor the size of the event handling queue.',
            'variable' => 'Number of handlers in the event_queue_handlers table'
        )
    )
```

### nagios_status callback

This does the actual service checking. The function should have the signature XXX_nagios_status($service, $params = null), and return a data object in the format described in the next section.

### Status result format

The result format is as follows:

```
array(
  'key'  => 'IDENTIFIER',
  'data' => array(
    'status' => STATUS_CODE,
    'type    => 'state', // Can be a 'state' for OK, Warning, Critical, Unknown) or can be 'perf', which does
                         // Cause an alert, but can be processed later by custom programs
    'text'   => 'Text description for the problem',
  ),
);
```

The \local_nagios\service object has constants for the status code, e.g. \local_nagios\service::NAGIOS_STATUS_WARNING

## Notes

1. The above describes how the Nagios plugin should be set up for an environment where Nagios and Moodle are running on the same server. This is unlikely to be the case for most installations, where the two systems may be on different servers. In this case, the Nagios NRPE system should be used.
2. The Nagios plugin is intended to be run on a Linux platform, although it may be possible to get it to work under Windows.