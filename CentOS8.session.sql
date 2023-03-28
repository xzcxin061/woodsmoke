use centos8;

select @@session.sql_mode;
-- {
--   "@@session.sql_mode": "IGNORE_SPACE,ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
-- }

-- 查询每个窝窝最新一条的评论
SELECT wo_id,any_value(created_at) created_at,any_value(id) id,any_value(content) content FROM `crawler_wowo_comment` group by wo_id ORDER BY wo_id ASC,created_at DESC;

-- 已将查询结果存储在wowo_comment表中

drop table c_products;