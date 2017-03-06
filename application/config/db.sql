/**
 * Author:  Seldoon <Sedloon@sina.cn>
 * Created: Mar 6, 2017
 */
CREATE TABLE `user` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `username` varchar(25) NOT NULL COMMENT '用户名',
    `password` varchar(254) NOT NULL COMMENT '密码',
    `mobile` varchar(11) NOT NULL  DEFAULT '' COMMENT '手机号',
    `email` varchar(128) NOT NULL DEFAULT '' COMMENT '邮箱',
    `created` int(11) unsigned NOT NULL COMMENT '创建时间',
    `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE `node` (
    `nid` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '节点id，自增',
    `type` VARCHAR (32) NOT NULL COMMENT '内容类型',
    `title` VARCHAR (255) NOT NULL DEFAULT '' COMMENT '内容标题',
    `uid` INT (11) UNSIGNED NOT NULL COMMENT '作者id',
    `status` TINYINT (1) UNSIGNED NOT NULL COMMENT '内容的状态',
    `comment` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数',
    `created` INT (11) UNSIGNED NOT NULL COMMENT '创建时间',
    `updated` INT (11) UNSIGNED NOT NULL COMMENT '更新时间',
    PRIMARY KEY (`nid`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 COMMENT = '内容节点表';

CREATE TABLE `comment` (
    `cid` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '评论的自增id',
    `nid` INT (11) UNSIGNED NOT NULL COMMENT '节点id',
    `subject` VARCHAR (255) NOT NULL DEFAULT '' COMMENT '回复的主题',
    `ip` VARCHAR (16) NOT NULL DEFAULT '' COMMENT '主机地址',
    `status` TINYINT (1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态',
    `username` VARCHAR (32) NOT NULL DEFAULT '' COMMENT '用户名称',
    `email` VARCHAR (128) NOT NULL DEFAULT '' COMMENT '电子邮箱',
    `created` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
    `updated` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`cid`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 COMMENT = '评论表';