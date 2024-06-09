<?php

$table_topic = Config::Get('db.table.topic'); // ls_topic
$table_vote = Config::Get('db.table.vote'); // ls_vote

$sql = "
                UPDATE ls_topic t
                SET t.topic_count_vote_up = (
                    SELECT count(*)
                    FROM ls_vote v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_down = (
                    SELECT count(*)
                    FROM ls_vote v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = -1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_abstain = (
                    SELECT count(*)
                    FROM ls_vote v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 0
                    AND
                        v.target_type = 'topic'
                )
            ";
