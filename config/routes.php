<?php
use Library\Route;

return  array(
    // site routes
    'default' => new Route('/', 'Site', 'index'),
    'index' => new Route('/index.php', 'Site', 'index'),
    'category' => new Route('/category/{id}', 'Category', 'index', array('id' => '[0-9]+')),
    'post'=> new Route('/post/{id}', 'Post', 'index', array('id' => '[0-9]+')),
    'tag'=> new Route('/tag/{id}', 'Tag', 'index', array('id' => '.+')),
    'tag_show' => new Route('/post/show/?', 'Tag', 'show'),
    'post_views_update' => new Route('/post/viewupdate/{id}', 'Post', 'update', array('id' => '[0-9_]+')),
    'category_analitics' => new Route('/analitics/?', 'Category', 'analitics'),
    'comment' => new Route('/post/comment/?', 'Post', 'comment'),
    'post_like' => new Route('/post/like/{id}', 'Post', 'like', array('id' => '[0-9]+')),
    'post_dislike' => new Route('/post/dislike/{id}', 'Post', 'dislike', array('id' => '[0-9]+')),
    'comment_change_by_id' => new Route('/post/comment/id/?', 'Post', 'change'),
    'comments_by_user_id' => new Route('/comment/{id}', 'Site', 'comment', array('id' => '[0-9]+')),
    'search' => new Route('/search/?', 'Site', 'search'),

    'login' => new Route('/login/?', 'Security', 'login'),
    'logout' => new Route('/logout/?', 'Security', 'logout'),
    'register' => new Route('/register', 'Security', 'register'),

    // admin routes
    'admin_default' => new Route('/admin/?', 'Admin\\Default', 'index'),
    'admin_category' => new Route('/admin/category/?', 'Admin\\Category', 'index'),
    'admin_category_edit' => new Route('/admin/category/edit/{id}', 'Admin\\Category', 'edit', array('id' => '[0-9]+')),
    'admin_category_delete' => new Route('/admin/category/delete/{id}', 'Admin\\Category', 'delete', array('id' => '[0-9]+')),
    'admin_category_add' => new Route('/admin/category/edit/?', 'Admin\\Category', 'add'),
    'admin_post' => new Route('/admin/post/?', 'Admin\\Post', 'index'),
    'admin_post_edit' => new Route('/admin/post/edit/{id}', 'Admin\\Post', 'edit', array('id' => '[0-9]+')),
    'admin_post_delete' => new Route('/admin/post/delete/{id}', 'Admin\\Post', 'delete', array('id' => '[0-9]+')),
    'admin_post_add' => new Route('/admin/post/edit/?', 'Admin\\Post', 'add'),
    'admin_comment' => new Route('/admin/comment/?', 'Admin\\Comment', 'index'),
    'admin_comment_edit' => new Route('/admin/comment/edit/{id}', 'Admin\\Comment', 'edit', array('id' => '[0-9]+')),
    'admin_comment_delete' => new Route('/admin/comment/delete/{id}', 'Admin\\Comment', 'delete', array('id' => '[0-9]+')),
    'admin_comment_approve_list' => new Route('/admin/comment/approve/?', 'Admin\\Comment', 'approve'),
    'admin_comment_approve' => new Route('/admin/approve/{id}', 'Admin\\Comment', 'check', array('id' => '[0-9]+')),
    'admin_advert' => new Route('/admin/advert/?', 'Admin\\Advert', 'index'),
    'admin_advert_edit' => new Route('/admin/advert/edit/{id}', 'Admin\\Advert', 'edit', array('id' => '[0-9]+')),
    'admin_advert_delete' => new Route('/admin/advert/delete/{id}', 'Admin\\Advert', 'delete', array('id' => '[0-9]+')),
    'admin_advert_add' => new Route('/admin/advert/edit/?', 'Admin\\Advert', 'add'),
    'admin_edit_css' => new Route('/admin/editcss/?', 'Admin\\Css', 'edit'),
    'admin_css_edit' => new Route('/admin/editcss/{id}', 'Admin\\Css', 'editb', array('id' => '[a-z0-9]+')),
    'admin_css_editNav' => new Route('/admin/editcssnav/{id}', 'Admin\\Css', 'editNav', array('id' => '[a-z0-9]+')),
    'admin_edit_menu' => new Route('/admin/menu/?', 'Admin\\Menu', 'index'),
    'admin_menu_edit' => new Route('/admin/menu/edit/{id}', 'Admin\\Menu', 'edit', array('id' => '[0-9]+')),
    'admin_menu_delete' => new Route('/admin/menu/delete/{id}', 'Admin\\Menu', 'delete', array('id' => '[0-9]+')),
    'admin_menu_add' => new Route('/admin/menu/add/?', 'Admin\\Menu', 'add')
);