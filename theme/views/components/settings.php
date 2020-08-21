
<div id="settings" class="settingswrap container">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo \home_url() ?>"><button class="backhome"><span class="backarrow"></span>Back</button></a>
        </div>
        <div id="settings_left" class="col-12 col-md-4">
            <h1 class="mobileonly settings-title">My Profile</h1>
            <div class="avatar full">
                <!--<i class="neonbadge avatar-svg" data-primary="<?php echo $this->neon_personal ?>" data-secondary="<?php echo $this->neon_complementary ?>" data-tertiary="<?php echo $this->neon_contrast ?>"></i> -->            
                <?php
                if( $this->avatar_img_id){
                    $img_url = wp_get_attachment_image_src( $this->avatar_img_id, 'neon_avatar_large', false );
                    if( $img_url ) $img_url = $img_url[0];
            
                }
                if( $this->avatar_img_id == false || $img_url === false ){
                    //can't get image, let's try gravatar
                    $img_url = \get_avatar_url( $this->user_email );
                }
                if( $img_url ){
                    echo '<img class="ghc avatar-img" src="'.$img_url.'">';
                }
                ?>
            </div>
            <!-- avatar full -->
            <div class="info">
            
                <h3 class="settings-name"><?php #echo $this->display_name ?></h3>
                <a class="change-profile-image" id="changeProfileLink" data-userid="<?php echo $this->ID?>" href="#">Change profile photo</a>
                    
            </div>
        </div>
        <div id="settings_right" class="col-12 col-md-8">
            <span id="profile-updated" class="system-dialogue"></span>
            <h1 class="settings-title desktoponly">My Info</h1>
            <div class="form-wrap">
                
                <div class="settingsform">
                    <label for="full_name">Full Name</label>
                    <!--<a class="change" onclick="setName(event);return false;" data-info="<?php echo $this->ID ?>" data-target="#full_name" href="#">Change</a> -->
                    <input id="full_name" name="full_name" type="text" placeholder="<?php echo $this->display_name ?>" readonly="readonly">
                </div>
                <div class="settingsform">
                    <label for="email">Email</label>
                    <!--<a class="change" onclick="setEmail(event);return false;" data-info="<?php echo $this->ID ?>" data-target="#email" href="#">Change</a> -->
                    <input id="email" name="email" type="email" placeholder="<?php echo $this->user_email ?>" readonly="readonly">
                </div>
                <div class="settingsform">
                    <label for="password">Password</label>
                    <a class="change" onclick="setPassword(event);return false;" data-compare="#passwordconfirm" data-info="<?php echo $this->ID ?>" data-target="#password" href="#">Change</a>
                    <input class="active" type="password" id="password" name="password" minlength="8" required placeholder="••••••••" readonly="readonly">
                    
                </div>
                <div id="password_confirm" class="settingsform passwordconfirm">
                    <label class="confirm" for="passwordconfirm">Password Confirm</label>
                    <a class="change confirming" onclick="setPassword(event);return false;" data-compare="#passwordconfirm" data-info="<?php echo $this->ID ?>" data-target="#password" href="#">Confirm</a>
                    <input class="inactive" type="password" id="passwordconfirm" name="passwordconfirm" minlength="8" required placeholder="">
                    
                </div>

                <div class="settings_checks">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <a class="agree-link" href="<?php echo \get_site_url('/terms-and-conditions/') ?>">Greenheart Connects Terms and Conditions</a>
                            </div>
                            <div class="col-12 col-md-6">
                                <a id="delete-account-link" class="manage-account" href=echo \get_site_url('/manage-account/') ?>">Manage my Greenheart Connects membership</a>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>