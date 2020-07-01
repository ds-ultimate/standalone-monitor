<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class saveAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates new entries for all statistics';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        /proc/uptime
        
        All values are 32 / 64 Bit values (unsigned long) wrapping possible!!!!
        
        Com alter db -> command_alter_db
        Com alter table -> command_alter_table
        Com change db -> command_change_db
        Com create db -> command_create_db
        Com create table -> command_create_table
        Com create index -> command_create_index
        Com delete -> command_delete
        Com drop db -> command_drop_db
        Com drop table -> command_drop_table
        Com insert -> command_insert
        Com select -> command_select
        Com set option -> command_set_option
        Com show status -> command_show_status
        Com update -> command_update
         */
        
        saveCpu::do_saving();
        saveDiskIo::do_saving();
        saveDiskUsage::do_saving();
        saveLoad::do_saving();
        saveMemory::do_saving();
        saveNetwork::do_saving();
        saveSql::do_saving();
        saveSsh::do_saving();
    }
}
