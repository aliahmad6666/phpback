<div class="row">
    <div class="col-md-10 col-md-offset-1 dashboard-center">
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="navbar-header">
                <div class="logosmall">
                    <img src="<?php echo base_url() . 'public/img/logo_small_free.png'?>">
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-01">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo base_url() . 'admin'; ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url() . 'admin/ideas'; ?>">Ideas and Comments</a></li>
                    <li><a href="<?php echo base_url() . 'admin/users'; ?>">Users Management</a></li>
                    <?php if($_SESSION['phpback_isadmin'] == 3){ ?>
                        <li><a href="<?php echo base_url() . 'admin/system'; ?>">System Settings</a></li>
                    <?php } if($_SESSION['phpback_isadmin'] == 3){ ?>
                        <li class="active"><a href="<?php echo base_url() . 'admin/permissions'; ?>">Permissions</a></li>
                    <?php } ?>
                </ul>
                <p class="navbar-text navbar-right">Signed in as <span style="color:#27AE60"><?php echo $_SESSION['phpback_username']; ?></span><a href="<?php echo base_url() . 'action/logout'; ?>"><button type="button" class="btn btn-danger btn-xs" style="margin-left:10px;">Log out</button></a></p>

            </div><!-- /.navbar-collapse -->
        </nav><!-- /navbar -->
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
<script>
    function showTab(tabId) {
        // إخفاء جميع الجداول
        document.getElementById('rolesTable').style.display = 'none';
        document.getElementById('rolesTable_li').classList.remove("active");
        document.getElementById('permissionsTable').style.display = 'none';
        document.getElementById('permissionsTable_li').classList.remove("active");
        document.getElementById('rolePermissionTable').style.display = 'none';
        document.getElementById('rolePermissionTable_li').classList.remove("active");
        document.getElementById('userRoleTable').style.display = 'none';
        document.getElementById('userRoleTable_li').classList.remove("active");

        // إظهار الجدول المطلوب
        document.getElementById(tabId).style.display = 'block';
        document.getElementById(tabId+'_li').classList.add("active");
    }
</script>

<div class="container">
    <h4>Rules and Permissions Management</h4>
    <ul class="nav nav-tabs">
        <li  id="rolesTable_li" class="active"><a href="#" onclick="showTab('rolesTable')">Rules</a></li>
        <li id="permissionsTable_li"><a href="#" onclick="showTab('permissionsTable')">Permissions</a></li>
        <li id="rolePermissionTable_li"><a href="#" onclick="showTab('rolePermissionTable')">Rule Permissions</a></li>
        <li id="userRoleTable_li"><a href="#" onclick="showTab('userRoleTable')">User Rules</a></li>
    </ul>

    <!-- جدول إدارة الأدوار -->
    <div id="rolesTable">
        <form role="form" method="post" action="<?php echo base_url('admin/addRule'); ?>">
            <div class="form-group">
                <label>Rule Name</label>
                <input type="text" class="form-control" name="name" required style="width:300px">
            </div>
            <div class="form-group">
                <button name="add-rule" type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= $role->id ?></td>
                <td><?= $role->name ?></td>
                <td>
                    <a href="<?= base_url('admin/deleteRole/'.$role->id) ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <!-- جدول إدارة الصلاحيات -->
    <div id="permissionsTable" style="display: none;">
        <form role="form" method="post" action="<?php echo base_url('admin/addPermission'); ?>">
            <div class="form-group col-sm-4">
                <label>Permission Name</label>
                <input type="text" class="form-control" name="name" required style="width:300px">
            </div>
            <div class="form-group col-sm-4">
                <label>Controller</label>
                <input type="text" class="form-control" name="controller" required style="width:300px">
            </div>
            <div class="form-group col-sm-4">
                <label>Action</label>
                <input type="text" class="form-control" name="action" required style="width:300px">
            </div>
            <div class="form-group col-sm-4">
                <button name="add-permission" type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
        <table  class="table table-bordered" >
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Controller</th>
                <th>Action</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($permissions as $perm): ?>
                <tr>
                    <td><?= $perm->id ?></td>
                    <td><?= $perm->name ?></td>
                    <td><?= $perm->controller ?></td>
                    <td><?= $perm->action ?></td>
                    <td>
                        <a href="<?= base_url('admin/deletePermission/'.$perm->id) ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- جدول ربط الأدوار بالصلاحيات -->
    <div id="rolePermissionTable" style="display: none;">
            <form role="form" method="post" action="<?php echo base_url('admin/addRulePermission'); ?>">
                <div class="form-group">
                    <label>Rule</label>
                    <select class="form-control" name="rule3" style="width:300px">
                        <?php foreach ($roles as $rule): ?>
                            <option value="<?php echo $rule->id; ?>"><?php echo $rule->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Permission</label>
                    <select class="form-control" name="permission3" style="width:300px">
                        <?php foreach ($permissions as $permission): ?>
                            <option value="<?php echo $permission->id; ?>"><?php echo $permission->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button name="rule-permission" type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Rule</th>
                <th>Permission</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($role_permissions as $rp): ?>
                <tr>
                    <td><?= $rp->role_name ?></td>
                    <td><?= $rp->permission_name ?></td>
                    <td>
                        <a href="<?= base_url('admin/deleteRolePermissions/'.$rp->rule_id.'/'.$rp->auth_id) ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- جدول ربط المستخدمين بالأدوار -->
    <div id="userRoleTable" style="display: none;">
            <form role="form" method="post" action="<?php echo base_url('admin/addRuleToUser'); ?>">
                <div class="form-group">
                    <label>User</label>
                    <select class="form-control" name="user_id" style="width:300px">
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Rule</label>
                    <select class="form-control" name="rule_id" style="width:300px">
                        <?php foreach ($roles as $rule): ?>
                            <option value="<?php echo $rule->id; ?>"><?php echo $rule->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <button name="user-rule" type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        <table  class="table table-bordered" >
            <thead>
            <tr>
                <th>User name</th>
                <th>Rule</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($user_roles as $ur): ?>
                <tr>
                    <td><?= $ur->username ?></td>
                    <td><?= $ur->role_name ?></td>
                    <td>
                        <a href="<?= base_url('admin/deleteUserRoles/'.$ur->user_id.'/'.$ur->rule_id) ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
</div>