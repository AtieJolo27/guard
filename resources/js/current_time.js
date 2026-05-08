function update_clock(){
    const now = new Date;

    const timestring = now.toLocaleTimeString();
    
    document.getElementById('current_time').textContent= timestring;

    
}
    setInterval(update_clock, 1000);
update_clock();

