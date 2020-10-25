

/* $('#ville').hide();
 $('#pays').hide();
 $('#categorie').hide();*/


/*document.getElementById('capacite').addEventListener('blur', function () {
    $('#formCapacite').submit();

});
document.getElementById('prix').addEventListener('blur', function () {
    $('#formPrix').submit();

});*/
//---------------------------------------------------
$('.town').click(function () {
    /*if (window.location.href.indexOf('?') !== -1){
        var sep = '&'
    }else{
        var sep = '?'
    }*/
    var ref = window.location.href.indexOf('v=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'v=' + $(this).html() + ref_last2;
});

//---------------------------------------------------

$('.pays').click(function () {
    var ref = window.location.href.indexOf('p=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'p=' + $(this).html() + ref_last2;
});
//---------------------------------------------------

$('.categorie').click(function () {
    var ref = window.location.href.indexOf('c=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'c=' + $(this).html() + ref_last2;
});

//---------------------------------------------------

$('#date_arrivee').change(function () {
    var ref = window.location.href.indexOf('da=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'da=' + $(this).val() + ref_last2;
});

//---------------------------------------------------

$('#date_depart').change(function () {
    var ref = window.location.href.indexOf('dd=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'dd=' + $(this).val() + ref_last2;
});

//---------------------------------------------------

$('#prix').mouseup(function () {
    var ref = window.location.href.indexOf('pm=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'pm=' + $(this).val() + ref_last2;
});

//---------------------------------------------------

$('#capacite').mouseup(function () {
    var ref = window.location.href.indexOf('cm=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'cm=' + $(this).val() + ref_last2;
});

//---------------------------------------------------

$('.page-link').mouseup(function () {
    var ref = window.location.href.indexOf('elems=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'elems=' + $(this).html() + ref_last2;
});


//---------------------------------------------------

$('.url_page').click(function () {
    var ref = window.location.href.indexOf('page=');
    if (ref != -1){
        let ref2 = window.location.href.slice(0, (ref));
        window.location.href = ref2 + 'page=' + $(this).html();
    }else{
        window.location.href = window.location.href + '&page=' + $(this).html();
    }
});


//---------------------------------------------------

/* $('#tri').change(function () {
     console.log(window.location.href.indexOf('orderby='));
     if (window.location.href.indexOf('orderby=') != -1){
         var ref = window.location.href.indexOf('orderby=');
         var ref2 = window.location.href.slice(0, (ref));
         ref = 'orderby='
     }else{
         var ref2 = window.location.href;
         var ref = '&orderby=';
     }
     var val = $('#tri').val();

     window.location.href = ref2 + ref + val;
 });*/


//---------------------------------------------------

/*$('#crs').change(function () {
    console.log(window.location.href.indexOf('crs='));
    if (window.location.href.indexOf('crs=') != -1){
        var ref = window.location.href.indexOf('crs=');
        var ref2 = window.location.href.slice(0, (ref));
        ref = 'crs='
    }else{
        var ref2 = window.location.href;
        var ref = '&crs=';
    }
    var val = $('#crs').val();

    window.location.href = ref2 + ref + val;
});*/


//------------------------

$('#crs').change(function () {
    var ref = window.location.href.indexOf('crs=');
    let ref2 = window.location.href.slice(0, (ref));
    let ref_last = window.location.href.indexOf('&', ref);
    let ref_last2 = window.location.href.slice(ref_last);

    window.location.href = ref2 + 'crs=' + $(this).val() + ref_last2;
});

$('#tri').change(function () {
    var ref = window.location.href.indexOf('orderby=');
    let ref2 = window.location.href.slice(0, (ref));

    window.location.href = ref2 + 'orderby=' + $(this).val();
});


$(document).ready(function () {

    $('#capacite_val').html($('#capacite').val() + ' personne(s)');
    $('#prix_val').html($('#prix').val() + ' €');
});

$('#capacite').mousedown(function () {
    $(document).mousemove(function () {
        $('#capacite_val').html($('#capacite').val() + ' personne(s)')
    })
});
$('#prix').mousedown(function () {
    $(document).mousemove(function () {
        $('#prix_val').html($('#prix').val() + ' €')
    })
});






document.getElementById('villes').addEventListener('click', function () {
    $('#ville').toggle("slow");
    $('#fa-villes').toggleClass("fa-angle-down");
    $('#fa-villes').toggleClass("fa-angle-up");

});
document.getElementById('payss').addEventListener('click', function () {
    $('#pays').toggle("slow");
    $('#fa-pays').toggleClass("fa-angle-down");
    $('#fa-pays').toggleClass("fa-angle-up");

});
document.getElementById('categories').addEventListener('click', function () {
    $('#categorie').toggle("slow");
    $('#fa-categories').toggleClass("fa-angle-down");
    $('#fa-categories').toggleClass("fa-angle-up");

});



$('#filtres').click(function () {
    $('#filtre_hide').toggle();
    $('#fa_filtre').toggleClass('fa-sort-amount-up-alt');
    $('#fa_filtre').toggleClass('fa-sort-amount-down-alt');
});

