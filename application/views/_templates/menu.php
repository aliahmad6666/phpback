<div class="col-md-3">
    <div class="sidemenu shadow-sm p-3 rounded" style="background-color: #f8f9fa;">

        <!-- Search Form -->
        <div id="search" class="mb-4">
            <form action="<?php echo base_url() . 'home/search'; ?>" method="POST">
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" name="query" id="search--input" type="search" placeholder="<?php echo $lang['label_search']; ?>" style="border-radius: 20px;">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary" id="search--button" style="border-radius: 20px;">
                                <span class="fui-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Controls -->
        <div id="controls" class="mb-4">
            <div class="controlBar clearfix">
                <!-- Sorting Options -->
                <div class="btn-group pull-left orderBy hidden-sm hidden-xs">
                    <a class="btn btn-xs btn-outline-primary" href="<?php echo base_url() . 'home/search?order=top'; ?>" hx-target="main">Top</a>
                    <a class="btn btn-xs btn-outline-primary" href="<?php echo base_url() . 'home/search?order=new'; ?>" hx-target="main">New</a>
                </div>

                <!-- Tags Dropdown -->
                <div class="btn-group pull-left filterByTag">
                    <button class="btn btn-xs btn-outline-secondary dropdown-toggle" data-toggle="dropdown" style="border-radius: 20px;">
                        Tags <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 5px;">
                        <li><a href="<?php echo base_url() . 'home/search'; ?>" hx-vals='{"tag": ""}' hx-target="main">All Tags</a></li>
                        <?php foreach($tags as $tag) { ?>
                            <li><a href="<?php echo base_url() . 'home/search?tag='.$tag->id; ?>"  value='{"tag": "<?php echo $tag->name;?>"}' hx-target="main"><?php echo $tag->name;?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Post New Idea Button -->
        <div id="postidea" class="mb-4">
            <a href="<?php echo base_url() . 'home/postidea'; ?>">
                <button type="button" class="btn btn-primary btn-block" id="post-new-idea-button" style="border-radius: 20px;">
                    <?php echo $lang['label_post_new_idea'];?>
                    <span class="glyphicon glyphicon-plus" style="margin-left:5px;"></span>
                </button>
            </a>
        </div>

        <!-- Categories List -->
        <div id="categories">
            <h6 class="font-weight-bold mb-3"><?php echo $lang['label_categories']; ?></h6>
            <ul class="nav nav-pills nav-stacked">
                <?php foreach($categories as $cat) { ?>
                    <li <?php if(!$cat->ideas) echo 'class="disabled"';?>>
                        <a href="<?php echo $cat->url; ?>" class="d-flex justify-content-between align-items-center">
                            <?php echo $cat->name; ?>
                            <span class="badge badge-pill badge-secondary"><?php echo $cat->ideas; ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>
</div>
