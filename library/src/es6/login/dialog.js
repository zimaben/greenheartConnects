class Dialog {
    constructor(obj){
      this.text = (obj.text) ? obj.text : ''; 
      this.type = (obj.type) ? obj.type : 'message';
      this.position = (obj.position) ? obj.position : 'default';
      this.colorclass = (obj.colorclass) ? obj.colorclass : '';
      this.time = (obj.time) ? obj.time : 3000;
      this.parent = (obj.parent) ? obj.parent : null;
      this.returned = true;
      this.checkReturned = function(){
        return this.returned;
      };
      this.addConfirmButtons = async function(div){
        return new Promise((resolve, reject ) => {
          let confirm = document.createElement('SPAN');
          let deny = document.createElement('SPAN');
          confirm.classList.add('confirm_button');
          deny.classList.add('deny_button');
          div.appendChild(confirm);
          div.appendChild(deny);
          confirm.addEventListener('click', function(event){
            let msg = event.target.closest('DIV');
            msg.parentElement.removeChild(msg);
            resolve(true)});
          deny.addEventListener('click', function(e){
              let msg = e.target.closest('DIV');
              msg.parentElement.removeChild(msg);
              resolve(false)});
        });
      };
      this.addDismiss = async function(div){
        return new Promise((resolve, reject ) => {
          let dismiss = document.createElement('SPAN');
          dismiss.classList.add('dismiss_button');
          div.appendChild(dismiss);
          dismiss.addEventListener('click', function(event){
            let msg = event.target.closest('DIV');
            msg.parentElement.removeChild(msg);
            resolve(true);
          });
        });
      };  
      this.isQuery = function(str){
        let pattern = /^[.#\[]/;
        return pattern.test(str);
      };
      this.findBox = function(){
        if(this.parent === null) return null;
        if(typeof(this.parent) === 'string'){
          let is_query = this.isQuery(this.parent);
          let is_elem = (is_query) ? document.querySelector(this.parent) : document.getElementById(this.parent);
          this.parent = ( is_elem.hasOwnProperty('tagName') ) ? is_elem : null;
        }
  
        if( ! this.parent.hasOwnProperty('tagName') ) {
  
          this.parent = null
   
        }
  
        return (this.parent.getBoundingClientRect() ) ? this.parent.getBoundingClientRect() : null;
  
      };
      this.render = async function(){
        //if a confirm dialog is already happening, bail
        //dismiss all existing dialogs
        let dialogs = document.getElementsByClassName('system-dialog');
        if(dialogs.length){
            for(let dialog of dialogs){ dialog.remove();}
        }
        let the_background = document.createElement('div');
        let the_message = document.createElement('span');
        the_background.classList.add( 'system-dialog');
        the_message.classList.add('the_message');
        the_message.innerHTML = '<span class="header"></span><span class="dialog_text">'+this.text+'</span>';
        the_background.appendChild(the_message);
        if(this.colorclass) the_message.classList.add(this.colorclass);
  
        let is_box = this.findBox();
        
        if( typeof is_box === 'object' && is_box !== null ){
          //is_box is a DomRect object: see - https://developer.mozilla.org/en-US/docs/Web/API/DOMRect
          switch(this.position) {
            case 'topright':
              the_message.setAttribute('right', is_box.right - 10 );
              the_message.setAttribute('top', is_box.top - 10 );
              break;
            case 'topleft':
              the_message.setAttribute('left', is_box.left + 10 );
              the_message.setAttribute('top', is_box.top +  10 );
              break;
            case 'bottomleft':
              the_message.setAttribute('left', is_box.left + 10 );
              the_message.setAttribute('bottom', is_box.bottom -  10 );
              break;
            case 'deadcenter':
              the_message.setAttribute('left', is_box.x + (is_box.width / 2) - 15 ); //need to figure out all the padding & halve it
              the_message.setAttribute('top', is_box.y +  (is_box.height / 2) - 15 );
              break;
  
            default: 
            //default is parent bottom right
            the_message.setAttribute('right', is_box.right - 10 );
            the_message.setAttribute('bottom', is_box.bottom - 10 );
            break;
          }
        } 
        //let the_container = this.findContainer();
        let body = document.getElementsByTagName('BODY')[0];
  
        body.insertBefore(the_background, body.firstChild );
  
        if(this.type == 'message'){
          let time = this.time;
          setTimeout(function() {
              the_background.parentElement.removeChild(the_background);
              this.returned = true; 
          }, time);
        } else {
          the_background.classList.add(this.type);
          if(this.type === 'confirm'){
              this.returned = await this.addConfirmButtons(the_message);
            } else if(this.type === 'dismiss'){
              this.returned = await this.addDismiss(the_message);
            }
        }
      return this.returned;
      };
    this.render();
    };
    then(resolve, reject) {
      let x = this.render();
      resolve(x);
    };
};