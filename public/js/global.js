// Function to calculate and update --inner1Vh
function updateInnerVh() {
    // Calculate 1% of the viewport height
    let vh = window.innerHeight / 100;
    
    // Update the CSS variable --inner1Vh
    document.documentElement.style.setProperty('--inner1Vh', vh + 'px');
}

// Call updateInnerVh when the page loads
window.addEventListener('load', updateInnerVh);

// Call updateInnerVh when the window is resized
window.addEventListener('resize', updateInnerVh);