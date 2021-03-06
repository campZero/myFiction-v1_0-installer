<?php
/*
	Job definition for 'textblocks'
	eFiction upgrade from version 3.5.x
	
	2017-01-28: Update DB queries
*/

$fw->jobSteps = array(
	"add"	=> "Add a registration and a cookie consent page",
	);


function textblocks_add($job, $step)
{
	$fw = \Base::instance();
	
	$fw->db5->exec("INSERT INTO `{$fw->dbNew}textblocks` (`id`, `label`, `title`, `content`, `as_page`) VALUES
					( '1', 'welcome', 			'',						'Welcome text', 0),
					( '2', 'copyright', 		'',						'***', 0),
					( '3', 'help', 				'',						'Help text', 1),
					( '4', 'nothankyou', 		'',						'***', 0),
					( '5', 'printercopyright',	'',						'***', 0),
					( '6', 'rules', 			'',						'One archive to rule them all', 1),
					( '7', 'thankyou', 			'',						'***', 0),
					( '8', 'tos', 				'',						'Terms of service', 1),
					( '9', 'maintenance', 		'',						'Closed for maintenance', 0),
					('10', 'registration',		'__Registration',		'By registering, you consent to the following rules: No BS-ing!', 0),
					('11', 'eucookie',			'(EU) Cookie consent',	'Cookie stuff ...', '1');");
					
	$count = $fw->db5->count();
	$fw->db5->exec ( "UPDATE `{$fw->dbNew}process`SET `success` = 2, `items` = :items WHERE `id` = :id ", 
						[ 
							':items' => $count,
							':id' => $step['id']
						]
					);
}

?>