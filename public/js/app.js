const parentDOM = document.getElementById("msgBanner");
const msg = parentDOM.getElementsByClassName("confirmation");

// show delete confirmation banner oafter 5seconds
if(typeof msg !== 'undefinied'){
    function showBanner(){
        setTimeout(function(){
            const a = document.getElementsByClassName('confirmation')[0];
            // console.log(a)
            a.style.display = "none";
        },1*5000);
    }
    showBanner();
}

