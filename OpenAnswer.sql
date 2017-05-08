-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL323' */;


--
-- Definition of table `axfer`
--

DROP TABLE IF EXISTS `axfer`;
CREATE TABLE `axfer` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(45) NOT NULL,
  `caller_chan` text NOT NULL,
  `callee_chan` text NOT NULL,
  `meetme_room` text NOT NULL,
  `time` text NOT NULL,
  `unique_id` text NOT NULL,
  `status` text NOT NULL,
  `third_party` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table for attended transfers via AMI';

--
-- Dumping data for table `axfer`
--

/*!40000 ALTER TABLE `axfer` DISABLE KEYS */;
/*!40000 ALTER TABLE `axfer` ENABLE KEYS */;


--
-- Definition of table `call_barge`
--

DROP TABLE IF EXISTS `call_barge`;
CREATE TABLE `call_barge` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` varchar(45) NOT NULL,
  `usr_ext` varchar(45) NOT NULL,
  `spy_channel` text NOT NULL,
  `time` varchar(60) NOT NULL,
  `unique_id` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table is for recording sessions of call barging.';

--
-- Dumping data for table `call_barge`
--

/*!40000 ALTER TABLE `call_barge` DISABLE KEYS */;
/*!40000 ALTER TABLE `call_barge` ENABLE KEYS */;


--
-- Definition of table `call_park`
--

DROP TABLE IF EXISTS `call_park`;
CREATE TABLE `call_park` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(45) NOT NULL,
  `callee_chan` varchar(45) NOT NULL,
  `caller_chan` varchar(45) NOT NULL,
  `timeout` text,
  `orig_queue` varchar(45) default NULL,
  `time` varchar(60) NOT NULL,
  `unique_id` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table is to create a work-around to a call parking bug ';

--
-- Dumping data for table `call_park`
--

/*!40000 ALTER TABLE `call_park` DISABLE KEYS */;
/*!40000 ALTER TABLE `call_park` ENABLE KEYS */;


--
-- Definition of table `cdr`
--

DROP TABLE IF EXISTS `cdr`;
CREATE TABLE `cdr` (
  `calldate` datetime NOT NULL default '0000-00-00 00:00:00',
  `clid` varchar(80) NOT NULL default '',
  `src` varchar(80) NOT NULL default '',
  `dst` varchar(80) NOT NULL default '',
  `dcontext` varchar(80) NOT NULL default '',
  `channel` varchar(80) NOT NULL default '',
  `dstchannel` varchar(80) NOT NULL default '',
  `lastapp` varchar(80) NOT NULL default '',
  `lastdata` varchar(80) NOT NULL default '',
  `duration` int(11) NOT NULL default '0',
  `billsec` int(11) NOT NULL default '0',
  `disposition` varchar(45) NOT NULL default '',
  `amaflags` int(11) NOT NULL default '0',
  `accountcode` varchar(20) NOT NULL default '',
  `userfield` varchar(255) NOT NULL default '',
  `uniqueid` varchar(32) NOT NULL default '',
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` USING BTREE (`accountcode`,`uniqueid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cdr`
--

/*!40000 ALTER TABLE `cdr` DISABLE KEYS */;
/*!40000 ALTER TABLE `cdr` ENABLE KEYS */;


--
-- Definition of table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `client_number` varchar(45) default NULL,
  `company_name` varchar(45) NOT NULL,
  `contact` varchar(45) NOT NULL,
  `phy_address` text,
  `phy_city` text,
  `phy_state` varchar(45) default NULL,
  `phy_zip` varchar(45) default NULL,
  `phone` varchar(45) NOT NULL,
  `fax` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `mail_address` varchar(45) default NULL,
  `mail_city` varchar(45) default NULL,
  `mail_state` varchar(45) default NULL,
  `mail_zip` varchar(45) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_client_num` (`client_number`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table for storing clients';

--
-- Dumping data for table `clients`
--

/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;


--
-- Definition of table `ext_assignment`
--

DROP TABLE IF EXISTS `ext_assignment`;
CREATE TABLE `ext_assignment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `extension` varchar(45) NOT NULL,
  `client` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `extension` (`extension`),
  KEY `FK_ext_assignment_1` (`client`),
  CONSTRAINT `FK_ext_assignment_1` FOREIGN KEY (`client`) REFERENCES `clients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='For Assigning extensions to clients.';

--
-- Dumping data for table `ext_assignment`
--

/*!40000 ALTER TABLE `ext_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_assignment` ENABLE KEYS */;


--
-- Definition of table `form_assignment`
--

DROP TABLE IF EXISTS `form_assignment`;
CREATE TABLE `form_assignment` (
  `form_id` int(10) unsigned NOT NULL auto_increment,
  `client_id` int(10) unsigned NOT NULL,
  `active` int(10) unsigned default NULL,
  PRIMARY KEY  (`form_id`),
  KEY `FK_form_assignment_2` (`client_id`),
  CONSTRAINT `FK_form_assignment_1` FOREIGN KEY (`form_id`) REFERENCES `xml_forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_form_assignment_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='For assigning clients to xml forms';

--
-- Dumping data for table `form_assignment`
--

/*!40000 ALTER TABLE `form_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_assignment` ENABLE KEYS */;


--
-- Definition of table `form_assignment_queue`
--

DROP TABLE IF EXISTS `form_assignment_queue`;
CREATE TABLE `form_assignment_queue` (
  `form_id` int(10) unsigned NOT NULL,
  `queue_id` int(10) unsigned NOT NULL,
  `active` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`form_id`),
  KEY `FK_form_assignment_queue_2` (`queue_id`),
  CONSTRAINT `FK_form_assignment_queue_1` FOREIGN KEY (`form_id`) REFERENCES `xml_forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_form_assignment_queue_2` FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='For assigning queues to xml forms.';

--
-- Dumping data for table `form_assignment_queue`
--

/*!40000 ALTER TABLE `form_assignment_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_assignment_queue` ENABLE KEYS */;


--
-- Definition of table `originate`
--

DROP TABLE IF EXISTS `originate`;
CREATE TABLE `originate` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `channel` text NOT NULL,
  `tech` text,
  `exten` text,
  `timeout` varchar(30) default NULL,
  `call_id` text,
  `unique_id` text NOT NULL,
  `user_id` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='This table is for originating calls that need the channel re';

--
-- Dumping data for table `originate`
--

/*!40000 ALTER TABLE `originate` DISABLE KEYS */;
/*!40000 ALTER TABLE `originate` ENABLE KEYS */;


--
-- Definition of table `queue_assignment`
--

DROP TABLE IF EXISTS `queue_assignment`;
CREATE TABLE `queue_assignment` (
  `queue_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`queue_id`,`user_id`),
  KEY `FK_queue_assignment_2` (`user_id`),
  CONSTRAINT `FK_queue_assignment_1` FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_queue_assignment_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='For asssigning agents to queue login.';

--
-- Dumping data for table `queue_assignment`
--

/*!40000 ALTER TABLE `queue_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue_assignment` ENABLE KEYS */;


--
-- Definition of table `queues`
--

DROP TABLE IF EXISTS `queues`;
CREATE TABLE `queues` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `queue_name` varchar(45) NOT NULL,
  `queue_ext` varchar(45) NOT NULL,
  `strategy` varchar(45) NOT NULL,
  `sla` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`queue_name`,`queue_ext`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table for Queue Management';

--
-- Dumping data for table `queues`
--

/*!40000 ALTER TABLE `queues` DISABLE KEYS */;
/*!40000 ALTER TABLE `queues` ENABLE KEYS */;


--
-- Definition of table `recorded_calls`
--

DROP TABLE IF EXISTS `recorded_calls`;
CREATE TABLE `recorded_calls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `call_unique_id` text NOT NULL,
  `user_issued` int(10) unsigned NOT NULL default '0',
  `time` text NOT NULL,
  `blob` longblob,
  `url` text,
  `filename` text,
  PRIMARY KEY  (`id`,`user_issued`),
  KEY `FK_recorded_calls_1` (`user_issued`),
  CONSTRAINT `FK_recorded_calls_1` FOREIGN KEY (`user_issued`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table for tracking calls that have been recorded';

--
-- Dumping data for table `recorded_calls`
--

/*!40000 ALTER TABLE `recorded_calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `recorded_calls` ENABLE KEYS */;


--
-- Definition of table `role_assignment`
--

DROP TABLE IF EXISTS `role_assignment`;
CREATE TABLE `role_assignment` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`role_id`,`user_id`),
  KEY `FK_user_id` (`user_id`),
  CONSTRAINT `FK_role_id` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Used to assign roles to users. Each user can have multiple r';

--
-- Dumping data for table `role_assignment`
--

/*!40000 ALTER TABLE `role_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_assignment` ENABLE KEYS */;


--
-- Definition of table `role_perm`
--

DROP TABLE IF EXISTS `role_perm`;
CREATE TABLE `role_perm` (
  `role_id` int(10) unsigned NOT NULL,
  `add_user` int(10) unsigned default '0',
  `remove_user` int(10) unsigned default '0',
  `modify_user` int(10) unsigned default '0',
  `delete_user` int(10) unsigned default '0',
  `add_client` int(10) unsigned default '0',
  `remove_client` int(10) unsigned default '0',
  `modify_client` int(10) unsigned default '0',
  `delete_client` int(10) unsigned default '0',
  `xfer_call` int(10) unsigned default '0',
  `xfer_other_call` int(10) unsigned default '0',
  `barge_calls` int(10) unsigned default '0',
  `record_calls` int(10) unsigned default '0',
  `record_other_calls` int(10) unsigned default '0',
  `park_call` int(10) unsigned default '0',
  `park_other_calls` int(10) unsigned default '0',
  `play_record` int(10) unsigned default '0',
  `delete_record` int(10) unsigned default '0',
  `add_queue` int(10) unsigned default '0',
  `delete_queue` int(10) unsigned default '0',
  `modify_queue` int(10) unsigned default '0',
  `view_all_live_calls` int(10) unsigned default '0',
  `view_team_live_calls` int(10) unsigned default '0',
  `view_all_cdr` int(10) unsigned default '0',
  `view_team_cdr` int(10) unsigned default '0',
  `view_own_cdr` int(10) unsigned default '0',
  `add_team` int(10) unsigned default '0',
  `delete_team` int(10) unsigned default '0',
  `remove_team` int(10) unsigned default '0',
  `modify_team` int(10) unsigned default '0',
  `view_user_details` int(10) unsigned default '0',
  `add_role` int(10) unsigned default '0',
  `delete_role` int(10) unsigned default '0',
  `modify_role` int(10) unsigned default '0',
  `view_client_details` int(10) unsigned default '0',
  `view_all_agent_status` int(10) unsigned default '0',
  `view_all_live_stats` int(10) unsigned default '0',
  `view_team_live_stats` int(10) unsigned default '0',
  `view_team_agent_status` int(10) unsigned default '0',
  PRIMARY KEY  (`role_id`),
  UNIQUE KEY `role_id` USING BTREE (`role_id`),
  CONSTRAINT `role_perm_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Role Permissions';

--
-- Dumping data for table `role_perm`
--

/*!40000 ALTER TABLE `role_perm` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_perm` ENABLE KEYS */;


--
-- Definition of table `short_cdr`
--

DROP TABLE IF EXISTS `short_cdr`;
CREATE TABLE `short_cdr` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `number_dialed` varchar(45) NOT NULL,
  `in_trunk` varchar(45) NOT NULL,
  `caller_id` varchar(45) NOT NULL,
  `unique_id` varchar(45) NOT NULL,
  `agent_channel` varchar(45) NOT NULL,
  `time` varchar(45) NOT NULL,
  `queue` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=latin1 COMMENT='A smaller CDR for locating customer calls';

--
-- Dumping data for table `short_cdr`
--

/*!40000 ALTER TABLE `short_cdr` DISABLE KEYS */;
/*!40000 ALTER TABLE `short_cdr` ENABLE KEYS */;


--
-- Definition of table `submitted_forms`
--

DROP TABLE IF EXISTS `submitted_forms`;
CREATE TABLE `submitted_forms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned default NULL,
  `client_id` int(10) unsigned default NULL,
  `form_data` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_submitted_forms_1` (`user_id`),
  CONSTRAINT `FK_submitted_forms_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table for storing data submitted by users in XML format.';

--
-- Dumping data for table `submitted_forms`
--

/*!40000 ALTER TABLE `submitted_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `submitted_forms` ENABLE KEYS */;


--
-- Definition of table `team_assignment`
--

DROP TABLE IF EXISTS `team_assignment`;
CREATE TABLE `team_assignment` (
  `user_id` int(10) unsigned NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `leader` int(10) unsigned default NULL,
  `default` int(10) unsigned default NULL,
  PRIMARY KEY  (`user_id`,`team_id`),
  KEY `FK_team_assignment_2` (`team_id`),
  CONSTRAINT `FK_team_assignment_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_team_assignment_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table for assigning team members to teams and setting leader';

--
-- Dumping data for table `team_assignment`
--

/*!40000 ALTER TABLE `team_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_assignment` ENABLE KEYS */;


--
-- Definition of table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `team_name` text NOT NULL,
  `date_created` varchar(45) NOT NULL,
  `last_modified` varchar(45) default NULL,
  `active` int(10) unsigned NOT NULL,
  `max_members` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table for creating teams and hierarchies.';

--
-- Dumping data for table `teams`
--

/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;

--
-- Definition of table `user_perms`
--

DROP TABLE IF EXISTS `user_perms`;
CREATE TABLE `user_perms` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `add_user` tinyint(3) unsigned default '0',
  `remove_user` int(11) default '0',
  `modify_user` int(11) default '0',
  `delete_user` int(11) default '0',
  `add_client` int(11) default '0',
  `remove_client` int(11) default '0',
  `modify_client` int(11) default '0',
  `delete_client` int(11) default '0',
  `xfer_call` int(11) default '0',
  `xfer_other_call` int(11) default '0',
  `barge_calls` int(11) default '0',
  `record_calls` int(11) default '0',
  `record_other_calls` int(11) default '0',
  `park_call` int(11) default '0',
  `park_other_calls` int(11) default '0',
  `play_record` int(10) unsigned default '0',
  `delete_record` int(10) unsigned default '0',
  `add_queue` int(10) unsigned default '0',
  `delete_queue` int(10) unsigned default '0',
  `modify_queue` int(10) unsigned default '0',
  `view_all_live_calls` int(10) unsigned default '0',
  `view_team_live_calls` int(10) unsigned default '0',
  `view_all_cdr` int(10) unsigned default '0',
  `view_team_cdr` int(10) unsigned default '0',
  `view_own_cdr` int(10) unsigned default '0',
  `add_team` int(10) unsigned default '0',
  `delete_team` int(10) unsigned default '0',
  `remove_team` int(10) unsigned default '0',
  `modify_team` int(10) unsigned default '0',
  `view_user_details` int(10) unsigned default '0',
  `add_role` int(10) unsigned default '0',
  `delete_role` int(10) unsigned default '0',
  `modify_role` int(10) unsigned default '0',
  `view_client_details` int(10) unsigned default '0',
  `view_all_agent_status` int(10) unsigned default '0',
  `view_team_agent_status` int(10) unsigned default '0',
  `view_all_live_stats` int(10) unsigned default '0',
  `view_team_live_stats` int(10) unsigned default '0',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `Unique_IDs` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User permissions';

--
-- Dumping data for table `user_perms`
--

/*!40000 ALTER TABLE `user_perms` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_perms` ENABLE KEYS */;


--
-- Definition of table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(45) NOT NULL,
  `level` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='Roles for quick permission assignment';

--
-- Dumping data for table `user_roles`
--

/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varchar(45) NOT NULL default 'INVALID',
  `pass` text NOT NULL,
  `name` varchar(45) NOT NULL,
  `extension` varchar(45) default NULL,
  `email_address` varchar(45) default NULL,
  `date_created` varchar(45) default NULL,
  `last_login` varchar(45) default NULL,
  `disabled` int(10) unsigned default NULL,
  `tech` varchar(45) default NULL,
  `channel` text,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `unique_agentID` USING BTREE (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Agent Logins';

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


--
-- Definition of table `xml_forms`
--

DROP TABLE IF EXISTS `xml_forms`;
CREATE TABLE `xml_forms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `form` text NOT NULL,
  `created` varchar(45) NOT NULL,
  `last_modified` varchar(45) NOT NULL,
  `creator` int(10) unsigned NOT NULL,
  `modifier` int(10) unsigned NOT NULL,
  `common_name` text NOT NULL,
  PRIMARY KEY  (`id`,`creator`,`modifier`,`created`,`last_modified`),
  KEY `FK_xml_forms_1` (`creator`),
  KEY `FK_xml_forms_2` (`modifier`),
  CONSTRAINT `FK_xml_forms_1` FOREIGN KEY (`creator`) REFERENCES `users` (`user_id`),
  CONSTRAINT `FK_xml_forms_2` FOREIGN KEY (`modifier`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table for storing XML forms for agent presentation';

--
-- Dumping data for table `xml_forms`
--

/*!40000 ALTER TABLE `xml_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `xml_forms` ENABLE KEYS */;


--
-- Definition of procedure `client_full_search`
--

DROP PROCEDURE IF EXISTS `client_full_search`;

DELIMITER $$

CREATE DEFINER=`root`@`%` PROCEDURE `client_full_search`(q varchar(100))
BEGIN
    SELECT * FROM `clients` WHERE `client_number` LIKE concat('%',q,'%') OR `company_name` LIKE concat('%',q,'%') OR
    `contact` LIKE concat('%',q,'%') OR `phy_address` LIKE concat('%',q,'%') OR `phy_city` LIKE concat('%',q,'%') OR
    `phy_state` LIKE concat('%',q,'%') OR `phy_zip` LIKE concat('%',q,'%') OR `phone` LIKE concat('%',q,'%') OR
    `fax` LIKE concat('%',q,'%') OR `email` LIKE concat('%',q,'%') OR `mail_address` LIKE concat('%',q,'%') OR
    `mail_city` LIKE concat('%',q,'%') OR `mail_state` LIKE concat('%',q,'%') OR `mail_zip` LIKE concat('%',q,'%');
END $$

DELIMITER ;

--
-- Definition of procedure `queue_full_search`
--

DROP PROCEDURE IF EXISTS `queue_full_search`;

DELIMITER $$

CREATE DEFINER=`root`@`%` PROCEDURE `queue_full_search`(q varchar(100))
BEGIN
   SELECT * FROM `queues` WHERE `queue_name` LIKE concat('%',q,'%') OR `queue_ext` LIKE concat('%',q,'%') OR
   `strategy` LIKE concat('%',q,'%') OR `sla` LIKE concat('%',q,'%');
END $$

DELIMITER ;

--
-- Definition of procedure `user_full_search`
--

DROP PROCEDURE IF EXISTS `user_full_search`;

DELIMITER $$

CREATE DEFINER=`root`@`%` PROCEDURE `user_full_search`(q varchar(100))
BEGIN
     SELECT * FROM `users` WHERE `user_id` LIKE concat('%',q,'%') OR `user_name` LIKE concat('%',q,'%')
     OR `name` LIKE concat('%',q,'%') OR `extension` LIKE concat('%',q,'%') OR `email_address` LIKE concat('%',q,'%')
     OR `tech` LIKE concat('%',q,'%') OR `channel` LIKE concat('%',q,'%');
END $$

DELIMITER ;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
