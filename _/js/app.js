// Functions ------------------------------------------------------------------


// Page Load ------------------------------------------------------------------

$(() => {

    // JavaScript Setups
    $('form :input:first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();

    // Initiate GET request (AJAX-enabled)
    $(document).on('click', '[data-get]', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request (AJAX-enabled)
    $(document).on('click', '[data-post]', e => {
        e.preventDefault();

        // Confirm
        const text = e.target.dataset.confirm;
        if (text != undefined) {
            if (!confirm(text || 'Are you sure?')) {
                return;
            }
        }

        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // History back
    $('[data-back]').click(e => {
        e.preventDefault();
        document.referrer ? history.back() : location = '/';
    });

    // Reset form
    $('[type=reset]').click(e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Check all
    $('[data-check]').click(e => {
        e.preventDefault();
        const name = e.target.dataset.check;
        $(`[name='${name}']`).prop('checked', true);
    });

    // Uncheck all
    $('[data-uncheck]').click(e => {
        e.preventDefault();
        const name = e.target.dataset.uncheck;
        $(`[name='${name}']`).prop('checked', false);
    });

    // Row checkable
    $('[data-checkable]').click(e => {
        if ($(e.target).is('input,button,a')) return;
        $(e.currentTarget)
            .find(':checkbox')
            .prop('checked', (i, v) => !v);
    });

    // Photo preview
    $('label.upload input').change(e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        img.dataset.src ??= img.src;

        if (f && f.type.startsWith('image/')) {
            img.onload = e => URL.revokeObjectURL(img.src);
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });
    $('[data-fav]').click(e => {
        fav = e.target.dataset.fav;
        i = $(e.target)

        if (i.hasClass('fa-regular')){
            $.get("./addfav.php?id="+fav).done(res=>{
                if (res=="true"){
                    $(i[0]).removeClass('fa-regular');
                    $(i[0]).addClass('fa-solid');
                }else{
                    alert("Unable Add To Favourite, "+ res);
                }
            });
        }else{
            $.get("./removefav.php?id="+fav).done(res=>{
                if (res=="true"){
                    $(i[0]).addClass('fa-regular');
                    $(i[0]).removeClass('fa-solid');
                }else{
                    alert("Unable Remove From Favourite, "+ res);
                }
            });
        }
    });

    
});

function setCookie(cname, cvalue, exsec,path) {
    const d = new Date();
    d.setTime(d.getTime() + (exsec*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path="+path;
  }