use centos8;

select @@session.sql_mode;
-- {
--   "@@session.sql_mode": "IGNORE_SPACE,ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
-- }

-- 查询每个窝窝最新一条的评论
SELECT wo_id,any_value(created_at) created_at,any_value(id) id,any_value(content) content FROM `crawler_wowo_comment` group by wo_id ORDER BY wo_id ASC,created_at DESC;

-- 已将查询结果存储在wowo_comment表中


-- “可以驻车”：13768
SELECT * FROM `wowo_comment` WHERE 
    (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%');

-- “可以驻车”：2897
SELECT * FROM `wowo_comment` WHERE 
    (content like '%?%' or content like '%？%') 
    and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%'));

-- “可以驻车”：3883
select * from `wowo_comment` WHERE 
    (content like '%很好%' or content like '%非常好%' or content like '%特别好%' or content like '%没人管%' or content like '%一般%' or content like '%安静%' or content like '%吵%') 
    and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%')) 
    and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content like '%?%' or content like '%？%') and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%')));

-- “不可以驻车”：2136
select * from `wowo_comment` WHERE 
    (content like '%不能%进%' or content like '%不能%停%' or content like '%不让%进%' or content like '%不让%停%' or content like '%废%' or content like '封%' or content like '%修路%' or content like '%施工%') 
    and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%')) 
    and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE 
    (content like '%?%' or content like '%？%') and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%'))) 
    and wo_id not in(select wo_id from `wowo_comment` WHERE 
    (content like '%很好%' or content like '%非常好%' or content like '%特别好%' or content like '%没人管%' or content like '%一般%' or content like '%安静%' or content like '%吵%') and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%')) and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content like '%?%' or content like '%？%') and wo_id not in(SELECT wo_id FROM `wowo_comment` WHERE (content LIKE '%不错%' OR content LIKE '%收费%' OR content LIKE '%免费%' OR content LIKE '%元%' OR content LIKE '%费用%' OR content LIKE '%价格%' OR content LIKE '%钱%'))));