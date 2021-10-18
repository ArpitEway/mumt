<div class="row mt-2">
    <div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
        <ul class="nav flex-column nav-pills">
            <?php
            $class = '';
            $i=1;
                foreach($menu_headings as $heading){
                $class = ($i!=1) ? '' : "active show";
                if ($i==1) {$active_heading=$heading->id; }
                $i++;
                ?>
                <li class="nav-item mb-2">
                    <a class="nav-link <?=$class?> border" id="heading-<?=$heading->id?>-tab" data-toggle="tab" href="#heading-<?=$heading->id?>">
                        <span class="nav-text"><?=$heading->heading?></span>
                        <span class="nav-icon flot-right" >
                            <i class="flaticon2-fast-next"></i>
                        </span>
                    </a>
                </li>
                <?php
                }
            ?>
            
        </ul>
    </div>
    <div class="col-md-8 col-12 col-sm-12 menu-background p-3">
        <div class="tab-content">
            <?php
                $heading_id = '';
                $class = '';
                foreach($menus as $menu){
                    if($heading_id!=$menu->heading_id){
                        if($heading_id!=''){
                        ?>
                    </div>
                    </div>
                        <?php
                        }
         $class= ($active_heading==$menu->heading_id) ? 'active show' : '';
                    ?>
                    <div class="tab-pane fade <?=$class;?>" id="heading-<?=$menu->heading_id?>" role="tabpanel" aria-labelledby="heading-<?=$menu->heading_id?>-tab">
                        <div class="row">
                            <?php
                            $class ='';
                            $heading_id=$menu->heading_id;
                            }
                        ?>
                        <a class="border-0 custom-menu-item" href="<?=BASE_URL($menu->url);?>">
                            <div>
                                <span class="nav-text"><?=$menu->option?></span>
                            </div>
                        </a>
                    <?php
                }
            ?>
            </div>
        </div>
        </div>
    </div>
</div>  