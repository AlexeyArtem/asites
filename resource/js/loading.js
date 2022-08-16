function displayLoading(id){
    elem = document.getElementById(id); 
    state = elem.style.display; 
    if (state =='none') elem.style.display=''; 
}
function stopLoading(id) {
	elem = document.getElementById(id); 
    state = elem.style.display; 
    if (state =='') elem.style.display='none'; 
}