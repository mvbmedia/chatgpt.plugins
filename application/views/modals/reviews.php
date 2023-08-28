<?php
# display offers
if (!empty($result)) {
    # reviews
    foreach($result as $key => $row)
    {
        # set variables
        $vote = ($row['recommend'] == 'yes' ? 'upvote' : 'downvote');
        $recommend = ($row['recommend'] == 'yes' ? 'aan' : 'niet aan');
        ?>
        <div class="review">
            <div class="details">
                <div class="thumbnail" data-mood="<?=$row['rating'];?>">
                    <img src="/images/icons/users/<?=$row['thumbnail'];?>" alt="auteur van de beoordeling"  />
                </div>
                <div class="person">
                    <span class="name"><?=$row['name'];?> <small>(<?=$row['age'];?> jaar)</small></span>
                    <span class="date"><?=$this->functions->timestamp($row['created']);?></span>
                </div>
                <div class="rating" data-evaluate="<?= $row['slug'] ;?>">
                    <span class="stars"></span>
                    <span class="stars-filled" style="width: <?= round($row['rating'] * 20); ?>%"></span>
                </div>
            </div>
            <div class="comment">
                <span class="<?=$vote;?>">Ik raad <?=$row['offer'];?> <?=$recommend;?></span>
                <span class="title"><?=$row['title'];?></span>
                <p class="description"><?=$row['description'];?></p>
            </div>
            <?php
            # display strengths
            if (!empty($row['strengths'])){
                echo '<div class="strengths">';

                # display pros
                if (!empty($row['strengths']['pros'])){
                    echo '<ul>';
                    foreach($row['strengths']['pros'] as $strength)
                    {
                        echo '<li class="pro"><span>' . $strength['title'] . '</span></li>';
                    }
                    echo '</ul>';
                }

                # display cons
                if (!empty($row['strengths']['cons'])){
                    echo '<ul>';
                    foreach($row['strengths']['cons'] as $strength)
                    {
                        echo '<li class="con"><span>' . $strength['title'] . '</span></li>';
                    }
                    echo '</ul>';
                }

                echo '</div>';
            }
            ?>
            <ul class="share">
                <li><button type="button" data-like="<?=$row['ID'];?>" class="<?=($row['liked'] ? 'active' : '');?>"><img src="/images/icons/like.svg" alt="nuttige review" /> Nuttig (<span class="likes"><?=$row['likes'];?></span>)</button></li>
                <li><button type="button" data-href="/reviews/<?=$row['offer_slug'];?>/<?=$row['slug'];?>/"><img src="/images/icons/comment.svg" alt="naar de reacties" /> Reacties (<?=$row['comments'];?>)</button></li>
                <li><a href="/reviews/<?=$row['offer_slug'];?>/<?=$row['slug'];?>/">Lees meer</a></li>
                <li><a href="/offer/<?=$row['offer_slug'];?>/" target="_blank" rel="nofollow"><img src="/images/icons/share.svg" alt="naar de aanbieder" /><?=$row['offer']?></a></li>
            </ul>
        </div>
        <?php
        # end of reviews
    }
}