<div class="panel-group" id="accordion">
    <div class="panel panel-primary">
        <div class="panel-heading" id="searchHeading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#searchCollapse">Find a Pageant</a>
            </h4></div>
        <div id="searchCollapse" class="panel-collapse collapse in">

            <form role="form" id="pageant-search" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#pageant" aria-controls="pageant" role="tab" data-toggle="tab">Pageant</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
<!--                     <li role="presentation"><a href="#coach" aria-controls="coach" role="tab" data-toggle="tab">Coach</a></li>
 -->                </ul>
                <div class="panel-body tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="pageant">
                        <fieldset class="stages">
                            <legend>Phases of Competition</legend>

                            <?php
                            $stages = get_terms('stages', array('hide_empty' => false));
                            foreach ($stages as $stage) :
                                ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="<?php echo $stage->slug; ?>" /><?php echo $stage->name; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>

                        <fieldset class="ages">
                            <legend>Age Divisions</legend>

                            <?php
                            $ages = get_terms('age-divisions', array('hide_empty' => false));
                            foreach ($ages as $age) :
                                ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="<?php echo $age->slug; ?>" /><?php echo $age->name; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <fieldset class="roles">
                            <legend>Roles</legend>
                            <?php $roles = get_terms('roles', array('hide_empty' => false)); ?>
                            <?php foreach ($roles as $role) : ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="<?php echo $role->slug; ?>" /><?php echo $role->name; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>

                        <fieldset class="expertise">
                            <legend>Areas of Expertise</legend>
                            <?php $areas = get_terms('areas-of-expertise', array('hide_empty' => false)); ?>
                            <?php foreach ($areas as $area) : ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="<?php echo $area->slug; ?>" /><?php echo $area->name; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>

                    <!--
                    <div role="tabpanel" class="tab-pane fade" id="coach">
                        <fieldset class="expertise">
                            <legend>Areas of Expertise</legend>

                            <?php
//                            $areas = get_terms('areas-of-expertise', array('hide_empty' => false));
//                            foreach ($areas as $area) :
                            ?>
                            <div class="checkbox">
                                <label><input type="checkbox" name="<?php //echo $area->slug; ?>" /><?php //echo $area->name; ?></label>
                            </div>
                            <?php //endforeach; ?>
                        </fieldset>
                    </div>
                    -->

<!--                     <div role="tabpanel" class="tab-pane fade" id="director"><p>Director pane</p></div>
 -->
                </div>
                <div class="panel-footer">

                    <input type="submit" class="btn btn-primary" value="Search" />
                </div>
            </form>

        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading" id="resultsHeading">
            <h4 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#resultsCollapse">Search Results</a>
            </h4></div>
        <div id="resultsCollapse" class="panel-collapse collapse">
            <div class="panel-body search-results"></div>

        </div></div>
</div>