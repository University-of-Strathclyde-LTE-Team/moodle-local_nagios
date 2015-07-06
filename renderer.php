<?php

class local_nagios_renderer extends plugin_renderer_base {

    public function render_servicelist($servicelist) {
        $table = new html_table();
        $table->head = array('Plugin', 'Service name', 'Class', 'Description', 'Variable');
        foreach ($servicelist as $plugin => $pluginservices) {
            foreach ($pluginservices as $name => $pluginservice) {
                $row = new html_table_row(array($plugin, $name, $pluginservice['classname']));
                $row->cells[] = new html_table_cell(get_string("local_nagios:$name:description", $plugin));
                $row->cells[] = new html_table_cell(get_string("local_nagios:$name:variable", $plugin));
                $table->data[] = $row;
            }
        }
        return html_writer::table($table);
    }

}