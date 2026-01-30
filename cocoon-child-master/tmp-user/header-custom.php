<?php
// ヘッダー切り替えロジック
if (cocoon_child_is_live_context()) {
    get_template_part('tmp-user/header', 'live');
} else {
    get_template_part('tmp-user/header', 'blog');
}
?>