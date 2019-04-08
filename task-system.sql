/*
Navicat MySQL Data Transfer

Source Server         :
Source Server Version :
Source Host           :
Source Database       : task-system

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2019-04-08 09:48:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for task_jobs
-- ----------------------------
DROP TABLE IF EXISTS `task_jobs`;
CREATE TABLE `task_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned NOT NULL COMMENT '消息ID',
  `data` text NOT NULL COMMENT '消息内容',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
