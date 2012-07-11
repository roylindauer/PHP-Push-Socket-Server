<?php
/**
 * Equipment Server
 *
 * @author Lab Analytix <http://labanalytix.com>
 * @version 0.1
 */

/* Configuration ************************************************************/

$servers[0] = array(
	'address' => '192.168.1.14', 			// ip address of socket server to create, typically the ip of the server this is run on
	'port' => 5001, 						// port of socket server to create
	'verboseMode' => true, 					// verbose mode.. true for loud, false for quiet
	'equipment_driver'=>'AD_fx300i.php', 	// equipment program to load
	'equipment_host'=>'192.168.1.144', 		// ip address of equipment to read from (192.168.1.144 for dev; 208.85.204.122 for staging with scale at Lab Analytix
	'equipment_port' => 20023, 				// port of equipment to read from
);
$servers[1] = array(
	'address' => '192.168.1.14',
	'port' => 5002,
	'verboseMode' => true,
	'equipment_driver'=>'AD_fx300i.php',
	'equipment_host'=>'192.168.1.144',
	'equipment_port' => 20024,
);
$servers[2] = array(
	'address' => '192.168.1.14',
	'port' => 5003,
	'verboseMode' => true,
	'equipment_driver'=>'AD_fx300i.php',
	'equipment_host'=>'192.168.1.144',
	'equipment_port' => 20025,
);

/* Common Functions *********************************************************/

//  found this code in the php manual for socket_read; 
// it seems to handle the block reading quite well; does the fread do the same?
function socket_read_normal($socket, $end=array("\r", "\n")){
    if(is_array($end)){
        foreach($end as $k=>$v){
            $end[$k]=$v{0};
        }
        $string='';
        while(TRUE){
            if (($char=socket_read($socket,1)) === false)
			{
				return false;
			}
            $string.=$char;
            foreach($end as $k=>$v){
                if($char==$v){
                    return $string;
                }
            }
        }
    }else{
        $endr=str_split($end);
        $try=count($endr);
        $string='';
        while(TRUE){
            $ver=0;
            foreach($endr as $k=>$v){
	            if (($char=socket_read($socket,1)) === false)
				{
					return false;
				}
                $string.=$char;
                if($char==$v){
                    $ver++;
                }else{
                    break;
                }
                if($ver==$try){
                    return $string;
                }
            }
        }
    }
}
?>