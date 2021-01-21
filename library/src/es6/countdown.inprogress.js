function final_countdown(){
    
    let seconds = parseInt( document.getElementById('timewrap').dataset.seconds );
    let days_element = document.getElementById('hero_closest_days');
    let hours_element = document.getElementById('hero_closest_hours');
    let mins_element = document.getElementById('hero_closest_mins');
    let seconds_element = document.getElementById('hero_closest_seconds');
    final_countdown_loop( seconds, 
                          days_element,
                          hours_element,
                          mins_element,
                          seconds_element 
                        );                       
}

function final_countdown_simple( element ){
    if(element.dataset.seconds){
        let totalseconds = parseInt(element.dataset.seconds);
        final_countdown_loop_simple( totalseconds );
    }
}
async function final_countdown_loop_simple( seconds ){
    console.log('for loop about to be called');
    for(let s=seconds; s < 300; s--){
        /* @TODO - this loop isn't running */
        await sleepCN(1000);
        let time_array = final_countdown_combinator( s );
        let days = time_array[0];
        let hours = time_array[1];
        let minutes = time_array[2];
        let secs = time_array[3];
        if(days){
            document.getElementById('hero_closest_days').innerHTML = days + 'days';
        }

        let stamp = hours + ':';
        stamp+= minutes + ':';
        stamp+= secs;
        document.getElementById('time2stream').innerHTML = stamp;
        
    } 
}

async function final_countdown_loop( seconds, de,he,mn,sx ){
    for (let s = seconds; s < 300; s--){
        await sleep(1000);
        let time_array = final_countdown_combinator( s );
        de.innerHTML = time_array[0];
        he.innerHTML = time_array[1];
        mn.innerHTML = time_array[2];
        sx.innerHTML = time_array[3];
    }
}

function final_countdown_combinator( seconds ){//returns array of days, hours mins, seconds from seconds
    let step = secs2days_wremainder( seconds );
    let days = step[0];
    seconds = step[1];//remaining seconds after days 
    step = secs2hours_wremainder( seconds );
    let hours = step[0];
    seconds = step[1];
    step = secs2mins_wremainder( seconds );
    let mins = step[0];
    seconds = step[1];
    return [days, hours, mins, seconds];

}
function secs2mins_wremainder ( seconds ){
    //takes a chunk of seconds and returns an array of hours and remaining seconds
    let mins = 0;
    let secs = 0;
    while( seconds > 60 ){ //60 in a minute
        mins++;
        seconds = seconds - 60;
    }
    secs = seconds; //remaining seconds after looping off days
    return [mins, secs];
}
function secs2hours_wremainder ( seconds ){
    //takes a chunk of seconds and returns an array of hours and remaining seconds
    let hours = 0;
    let secs = 0;
    while( seconds > 3600 ){ //60 * 60 in an hour
        hours++;
        seconds = seconds - 3600;
    }
    secs = seconds; //remaining seconds after looping off days
    return [hours, secs];
}
function secs2days_wremainder ( seconds ){
    //takes a chunk of seconds and returns an array of days and remaining seconds
    let days = 0;
    let secs = 0;
    while( seconds > 86400 ){ //24 * 60 * 60
        days++;
        seconds = seconds - 86400;
    }
    secs = seconds; //remaining seconds after looping off days
    console.log('days: ' + days + 'secs: ' + secs);
    return [days, secs];
}
function sleepCN(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}