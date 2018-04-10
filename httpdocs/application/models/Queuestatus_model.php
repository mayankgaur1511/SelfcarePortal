<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queuestatus_model extends CI_Model {

    function run()
    {
        $this->db->where('(running = 1 and lastRun > DATEADD(MINUTE,-2,GETDATE()))');

        if(!$this->db->get('queueStatus')->num_rows())
        {
            $this->db->query("UPDATE queueStatus SET running = 1, lastRun = GETDATE()");
            return true;
        }
        
        return false;
    }

    function clean()
    {
        return $this->db->update('queueStatus',array('running' =>0 ));
    }
}