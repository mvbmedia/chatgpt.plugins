<?php
# initiate modal
$app = new Evaluate;

# display head
$this->template->display('app/head', [
    # meta's
    'meta_title' => 'Wat vind jij van ' .$this->data['subject']['name'] . '? Deel je ervaring!',
    'meta_description' =>  'Door jou ervaring met ' .$this->data['subject']['name'] . ' te delen op ChatGPT Plugins, help je andere gebruikers bij het maken van de juiste keuze!',
    # open graph tags
    'og_image' => '/images/thumbnails/' . $this->data['subject']['thumbnail'],

    # canonical
    'canonical' => '/evaluate/' . $this->data['subject']['slug'] . '/'
]);

# display menu
$this->template->display('app/menu', [
	'status' => 'active'
]);
?>
<?php
    global $nonce;
?>
<script nonce="<?php echo $nonce; ?>">
/* display next step */
$(document).on('click', '.btn-next', function(){
	/* total steps */
	let step = $('.evaluate-step');

	/* display next step */
	$('.evaluate-step').each(function(count){
		if ($(this).is(':visible')){
			/* invalid experience */
			if ($(this).children().hasClass('experience')){
				if (!$('input[name=title]').val() || !$('textarea[name=description]').val()){
					alert("Je hebt geen beoordeling geschreven");
					return false;
				}
			}
			
			/* invalid recommend */
			if ($(this).children().hasClass('recommend')){
				if (!$('input[name=recommend]').is(':checked')){
					alert("Zou je <?=$this->data['subject']['name'];?> aanraden?");
					return false;
				}
			}
			
			/* invalid gender */
			if ($(this).children().hasClass('gender')){
				if (!$('input[name=gender]').is(':checked')){
					alert("Wat is jouw geslacht?");
					return false;
				}
			}
			
			/* hide current step */
			$(this).hide();
			
			/* set next step */
			var next = step.eq(count + 1);
						
			/* display next step */
			$(next).fadeIn(250).css('display', 'block');

			/* stop iteration */
			return false;
		}
	});
});

$(document).on('click', '.star-rating', function(){
    /* set variables */
    let subject = <?=$this->data['subject']['ID'];?>;
    let items = {};

    /* set rating */
    $('.star-rating:checked').each(function(i){
        items[i] = {};
        items[i]['id'] = $(this).data('question');
        items[i]['rating'] = $(this).val();
    });

    /* set data */
    let data = new FormData();
    data.append('subject', subject);
    data.append('rating', JSON.stringify(items));

    /* upload request */
    $.ajax({
        url: '/api/rating/',
        data: data,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: 'json'
    });
});

/* display previous step */
$(document).on('click', '.btn-prev', function(){
	/* total steps */
	var step = $('.evaluate-step');

	/* display previous step */
	$('.evaluate-step').each(function(count){
		if ($(this).is(':visible')){
			/* hide current step */
			$(this).hide();
			
			/* set previous step */
			var next = step.eq(count - 1);
						
			/* display previous step */
			$(next).fadeIn(250).css('display', 'block');
			
			/* stop iteration */
			return false;
		}
	});
});

/* cancel review */
$(document).on('click', '.btn-cancel', function(){
	window.location.href = "/reviews/<?=$this->data['subject']['slug'];?>/";
});

/* save review */
$(document).on('click', '.btn-save', function(){
	/* set variables */
    let subject = <?=$this->data['subject']['ID'];?>;
    let recommend = $('input[name=recommend]:checked').val();
    let recommendation = $('select[name=recommendation] option:selected').val();
    let title = $('input[name=title]').val();
    let description = $('textarea[name=description]').val();
    let gender = $('input[name=gender]:checked').val();
    let name = $('input[name=name]').val();
    let email = $('input[name=email]').val();
    let age = $('input[name=age]').val();
    let comments = ($('input[name=comments]').is(':checked') ? 'yes' : 'no');
    let newsletter = ($('input[name=newsletter]').is(':checked') ? 'yes' : 'no');
    let rating = [];
    let pros = [];
    let cons = [];
    let items = {};

    /* set rating */
    $('.star-rating:checked').each(function(i){
        items[i] = {};
        items[i]['id'] = $(this).data('question');
        items[i]['rating'] = $(this).val();
    });
	
	/* set pros */
	$('input[name^=pros]').each(function(){
		pros.push($(this).val());
	});
	
	/* set cons */
	$('input[name^=cons]').each(function(){
		cons.push($(this).val());
	});

	/* set data */
    let data = new FormData();
	data.append('subject', subject);
	data.append('recommend', recommend);
	data.append('recommendation', recommendation);
	data.append('title', title);
	data.append('description', description);
	data.append('gender', gender);
	data.append('name', name);
	data.append('email', email);
	data.append('age', age);
	data.append('comments', comments);
	data.append('newsletter', newsletter);
	data.append('rating', JSON.stringify(items));
	data.append('pros', JSON.stringify(pros));
	data.append('cons', JSON.stringify(cons));
	
	/* upload request */
	$.ajax({ 
		url: '/api/review/',
		data: data,
		type: 'POST',
		processData: false,
		contentType: false,
		dataType: 'json',
		success: function(response){
			/* display message */
			if (response['status'] === 'success'){
				alert(response['message']);
				window.location.href = "/reviews/<?=$this->data['subject']['slug'];?>/#comments";
			/* redirect */
			} else if (response['status'] === 'error'){
				alert(response['message']);
			}			
		},
		error: function(response){
			/* default message */
			alert('Er is een fout opgetreden, probeer het later nog een keer.', 'error');
		}
	});
});
</script>
<!-- breadcrumbs -->
<nav id="breadcrumbs" class="evaluate-crumbs" aria-label="Breadcrumb">
    <div class="wrapper">
         <ol itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php
            $breadcrumbs = [
                [
                    'name' => 'Home',
                    'href' => '/'
                ],
                [
                    'name' => 'Reviews',
                    'href' => '/reviews/'
                ],
                [
                    'name' => $this->data['subject']['name'],
                    'href' => '/reviews/' . $this->data['subject']['slug'] . '/'
                ],
                [
                    'name' => 'Review schrijven',
                    'href' => '/evaluate/' . $this->data['subject']['slug'] . '/'
                ]
            ];

            foreach ($breadcrumbs as $index => $breadcrumb) :
            ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="<?= $breadcrumb['href'] ?>">
                        <span itemprop="name"><?= $breadcrumb['name'] ?></span>
                    </a>
                    <meta itemprop="position" content="<?= $index + 1 ?>" />
                </li>
             <?php endforeach; ?>
        </ol>
    </div>
</nav>
<!-- banner -->
<header id="banner" class="evaluate-banner">
    <div class="wrapper">
        <div class="thumbnail">
            <img src="/images/symbols/110x110/<?= str_replace('.png', '.jpg', $this->data['subject']['symbol']); ?>" alt="logo <?=$this->data['subject']['name'];?>" loading="lazy" />
        </div>
        <div class="details">
            <h1>Wat vind jij van <?=$this->data['subject']['name'];?>?</h1>
            <h2>Beoordeel <?=$this->data['subject']['domain'];?></h2>
        </div>
    </div>
</header>
<!-- review -->
<div id="evaluate">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 evaluate-step">
                <div class="questions">
                    <ul>
                        <?php
                        foreach ($this->data['questions'] as $row) {
                            $title = strtolower(str_replace(' ', '-', $row['title']));
                            ?>
                            <li>
                                <span class="title"><?= $row['title']; ?></span>
                                <div class="stars">
                                    <input type="radio" class="star-rating" id="star-<?= $title; ?>-5" name="rating[<?= $row['ID']; ?>]" data-question="<?= $row['ID']; ?>" value="5"/><label for="star-<?= $title; ?>-5" title="Uitstekend - 5 sterren"></label>
                                    <input type="radio" class="star-rating" id="star-<?= $title; ?>-4" name="rating[<?= $row['ID']; ?>]" data-question="<?= $row['ID']; ?>" value="4"/><label for="star-<?= $title; ?>-4" title="Goed - 4 sterren"></label>
                                    <input type="radio" class="star-rating" id="star-<?= $title; ?>-3" name="rating[<?= $row['ID']; ?>]" data-question="<?= $row['ID']; ?>" value="3" checked/><label for="star-<?= $title; ?>-3" title="Gemiddeld - 3 sterren"></label>
                                    <input type="radio" class="star-rating" id="star-<?= $title; ?>-2" name="rating[<?= $row['ID']; ?>]" data-question="<?= $row['ID']; ?>" value="2"/><label for="star-<?= $title; ?>-2" title="Matig - 2 sterren"></label>
                                    <input type="radio" class="star-rating" id="star-<?= $title; ?>-1" name="rating[<?= $row['ID']; ?>]" data-question="<?= $row['ID']; ?>" value="1"/><label for="star-<?= $title; ?>-1" title="Slecht - 1 ster"></label>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                        <li>
                            <span class="title">Gemiddelde cijfer</span>
                            <div class="stars">
                                <input type="radio" class="star-average" id="star-average-5" value="5" disabled/><label for="star-average-5" class="disabled" title="Uitstekend - 5 sterren"></label>
                                <input type="radio" class="star-average" id="star-average-4" value="4" disabled/><label for="star-average-4" class="disabled" title="Goed - 4 sterren"></label>
                                <input type="radio" class="star-average" id="star-average-3" value="3" disabled checked/><label for="star-average-3" class="disabled" title="Gemiddeld - 3 sterren"></label>
                                <input type="radio" class="star-average" id="star-average-2" value="2" disabled/><label for="star-average-2" class="disabled" title="Matig - 2 sterren"></label>
                                <input type="radio" class="star-average" id="star-average-1" value="1" disabled/><label for="star-average-1" class="disabled" title="Slecht - 1 ster"></label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="steps">
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-cancel">Annuleren</button>
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-next">Volgende stap</button>
                </div>
            </div>
            <!-- experience -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 evaluate-step">
                <div class="experience">
                    <table>
                        <tr>
                            <td>
                                <span class="title">Deel jouw ervaring</span>
                                <span class="description">Mensen discussiëren over betrouwbaarheid, tevredenheid en meer</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="title" class="title">Geef je beoordeling een titel</label>
                                <input type="text" name="title" id="title" placeholder="Beschrijf jouw beoordeling in het kort" maxlength="255"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="write-review" class="title">Schrijf je beoordeling</label>
                                <textarea name="description" class="write-review" id="write-review" placeholder="Schrijf hier jouw recente ervaring" maxlength="5000"></textarea>
                                <span class="review-length"><span class="current-review-length">0</span> / 5000 tekens</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="steps">
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-prev">Vorige stap</button>
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-next">Volgende stap</button>
                </div>
            </div>
            <!-- recommend -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 evaluate-step">
                <div class="recommend">
                    <div class="item">
                        <span class="title">Zou je <?= $this->data['subject']['name']; ?> aanraden?</span>
                        <ul>
                            <li>
                                <input type="radio" id="approve" name="recommend" value="yes"/>
                                <label for="approve" class="approve" title="Ik raad dit aan.">
                                    <img src="/images/icons/thumbs-up.svg" alt="ik raad dit aan"/>
                                    <span>Ja</span>
                                </label>
                            </li>
                            <li>
                                <input type="radio" id="disapprove" name="recommend" value="no"/>
                                <label for="disapprove" class="disapprove" title="Ik raad dit niet aan.">
                                    <img src="/images/icons/thumbs-down.svg" alt="ik raad dit niet aan"/>
                                    <span>Nee</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="item">
                        <span class="title">Zou je een andere datingsite aanraden?</span>
                        <select name="recommendation">
                            <option value="0">Kies een datingsite</option>
                            <?php
                            # display subjects
                            foreach ($this->data['subjects'] as $row) {
                                echo '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="steps">
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-prev">Vorige stap</button>
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-next">Volgende stap</button>
                </div>
            </div>
            <!-- impressions -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 evaluate-step">
                <div class="impressions">
                    <div class="item">
                        <span class="title">Heeft <?= $this->data['subject']['name']; ?> voordelen?</span>
                        <ul>
                            <li><input type="text" name="pros[]" placeholder="Noem een eerste voordeel" maxlength="255"/></li>
                            <li><input type="text" name="pros[]" placeholder="Noem een tweede voordeel" maxlength="255"/></li>
                            <li><input type="text" name="pros[]" placeholder="Noem een laatste voordeel" maxlength="255"/></li>
                        </ul>
                    </div>
                    <div class="item">
                        <span class="title">Heeft <?= $this->data['subject']['name']; ?> nadelen?</span>
                        <ul>
                            <li><input type="text" name="cons[]" placeholder="Noem een eerste nadeel" maxlength="255"/></li>
                            <li><input type="text" name="cons[]" placeholder="Noem een tweede nadeel" maxlength="255"/></li>
                            <li><input type="text" name="cons[]" placeholder="Noem een laatste nadeel" maxlength="255"/></li>
                        </ul>
                    </div>
                </div>
                <div class="steps">
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-prev">Vorige stap</button>
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-next">Volgende stap</button>
                </div>
            </div>
            <!-- gender -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 evaluate-step">
                <div class="gender">
                    <span class="title">Wat is jouw geslacht?</span>
                    <ul>
                        <li>
                            <input type="radio" id="male" name="gender" value="male"/>
                            <label for="male" title="Man">
                                <img src="/images/icons/male.svg" alt="mannelijk geslacht"/>
                                <span>Man</span>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="female" name="gender" value="female"/>
                            <label for="female" title="Vrouw">
                                <img src="/images/icons/female.svg" alt="vrouwelijk geslacht"/>
                                <span>Vrouw</span>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="other" name="gender" value="other"/>
                            <label for="other" title="Anders">
                                <img src="/images/icons/neutral.svg" alt="ander geslacht"/>
                                <span>Anders</span>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="steps">
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-prev">Vorige stap</button>
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-next">Volgende stap</button>
                </div>
            </div>
            <!-- personal -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 evaluate-step">
                <div class="personal">
                    <table>
                        <tr>
                            <td colspan="2"><span class="title">Plaats jouw review met</span></td>
                        </tr>
                        <tr class="credentials">
                            <td>
                                <button type="button" class="btn btn-google btn-round btn-wide">Doorgaan met Google</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-facebook btn-round btn-wide">Doorgaan met Facebook</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="divider"><span>Of</span></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="name" placeholder="Jouw volledige naam" maxlength="255"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="email" name="email" placeholder="Jouw e-mailadres"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="number" name="age" placeholder="Jouw leeftijd" min="18" max="150" step="1" maxlength="3"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="agreement">
                                    <input type="checkbox" name="comments" id="new-reviews" value="yes" checked/>
                                    <label for="new-reviews">Breng mij op de hoogte bij nieuwe reviews over <?= $this->data['subject']['name']; ?></label>
                                </div>
                                <div class="agreement">
                                    <input type="checkbox" name="newsletter" id="newsletter" value="yes" checked/>
                                    <label for="newsletter">Schrijf me in voor de ChatGPT Plugins nieuwsbrief</label>
                                </div>
                                <span class="agreements">Door deze beoordeling te plaatsen gaat u akkoord met onze <a href="/algemene-voorwaarden/">gebruikersvoorwaarden</a> en <a href="/privacy/">privacybeleid</a></span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="steps">
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-prev">Vorige stap</button>
                    <button type="button" class="btn btn-danger btn-round btn-medium btn-save">Opslaan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->template->display('app/footer');
