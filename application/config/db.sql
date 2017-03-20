/**
 * Author:  Seldoon <Sedloon@sina.cn>
 * Created: Mar 6, 2017
 */
CREATE TABLE `user` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `username` VARCHAR(25) NOT NULL COMMENT '用户名',
    `password` VARCHAR(254) NOT NULL COMMENT '密码',
    `mobile` VARCHAR(11) NOT NULL  DEFAULT '' COMMENT '手机号',
    `email` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '邮箱',
    `data` TEXT NOT NULL DEFAULT '' COMMENT '用户数据',
    `created` INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
    `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE `node` (
    `nid` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '节点id，自增',
    `uid` INT (11) UNSIGNED NOT NULL COMMENT '作者id',
    `type` VARCHAR (32) NOT NULL COMMENT '内容类型',
    `title` VARCHAR (255) NOT NULL DEFAULT '' COMMENT '内容标题',
    'body' TEXT NOT NULL DEFAULT '' COMMENT '内容',
    `status` TINYINT (1) UNSIGNED NOT NULL COMMENT '内容的状态',
    `comment` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数',
    `created` INT (11) UNSIGNED NOT NULL COMMENT '创建时间',
    `updated` INT (11) UNSIGNED NOT NULL COMMENT '更新时间',
    PRIMARY KEY (`nid`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 COMMENT = '内容节点表';

CREATE TABLE `comment` (
    `cid` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '评论的自增id',
    `nid` INT (11) UNSIGNED NOT NULL COMMENT '节点id',
    `uid` INT (11) UNSIGNED NOT NULL COMMENT '用户id',
    `subject` VARCHAR (255) NOT NULL DEFAULT '' COMMENT '回复的主题',
    'body' TEXT NOT NULL DEFAULT '' COMMENT '内容',
    `ip` VARCHAR (16) NOT NULL DEFAULT '' COMMENT '主机地址',
    `status` TINYINT (1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态',
    `username` VARCHAR (32) NOT NULL DEFAULT '' COMMENT '用户名称',
    `email` VARCHAR (128) NOT NULL DEFAULT '' COMMENT '电子邮箱',
    `created` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
    `updated` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`cid`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 COMMENT = '评论表';

CREATE TABLE `todolist` (
    `id` INT (11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT  '待办事项自增id',
    `uid` INT (11) UNSIGNED NOT NULL COMMENT '用户id',
    `title` VARCHAR (255) NOT NULL DEFAULT '' COMMENT '标题',
    `description` TEXT NOT NULL DEFAULT '' COMMENT '内容描述',
    `created` INT (10) NOT NULL COMMENT '创建时间',
    `updated` INT (10) NOT NULL COMMENT '更新时间',
    `due_on` INT (10) NOT NULL COMMENT '到期时间',
    `comment` TEXT NOT NULL DEFAULT '' COMMENT '备注信息',
    `status` TINYINT (1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布状态',
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '待办事项表';