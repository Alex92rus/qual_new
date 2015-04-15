<?php exit; ?>
1427208753
SELECT m.*, u.user_colour, g.group_colour, g.group_type FROM (forummoderator_cache m) LEFT JOIN forumusers u ON (m.user_id = u.user_id) LEFT JOIN forumgroups g ON (m.group_id = g.group_id) WHERE m.display_on_index = 1
6
a:0:{}