document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
}, false);

document.addEventListener('keydown', function(e) {
    if (e.key === 'F12' ||
        (e.ctrlKey && e.shiftKey && e.key === 'I') ||
        (e.ctrlKey && e.shiftKey && e.key === 'C') ||
        (e.ctrlKey && e.shiftKey && e.key === 'J') ||
        (e.ctrlKey && e.key === 'U')) {
        e.preventDefault();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'U') {
        e.preventDefault();
    }
});


function disableRightClick() {
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    }, false);
}

disableRightClick();

// Monitor for re-enabling and disable again
setInterval(function() {
    if (document.oncontextmenu !== null) {
        disableRightClick();
    }
}, 1000);