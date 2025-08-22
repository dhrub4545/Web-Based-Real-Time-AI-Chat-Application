let currentuser="";
  function filterFunction(a) {
    // console.log(a);
        getnewusers(a);
          }
          function cleardropdown(){
            
            document.getElementById("myDropdown").style.display="none";
  }
  function getnewusers(a){
    document.getElementById("myDropdown").style.display="block";
    let input = document.getElementById("myInput").value;
    if (!input){
      cleardropdown();
      return;
    }
    const formData = new FormData();
        formData.append('key1', a);
        formData.append('key2', input);
        // console.log(val1,val2);
        fetch('tableinfo.php', {
          method: 'POST', // Specify the request method
          body: formData  // Pass the FormData object or any other data
        })
          .then(response => response.json())
          .then(data => {
            let tablediv = document.getElementById('myDropdown');
            tablediv.innerHTML="";
            data.forEach(message => {
              let messageElement = document.createElement('p');
              messageElement.textContent = message.tb;
              messageElement.onclick= function (){
                adduser(message.tb);
              };
              tablediv.appendChild(messageElement);
              // console.log(message.tb);
            });
            
          })
          .catch(error => console.error('Error fetching messages:', error));
      
  }
function gettables(a){
  // console.log(a);
  currentuser=a;
  // console.log(currentuser);
  function fetchtables(){
    // console.log("hi");
  const formData = new FormData();
        formData.append('key1',a);
        // console.log(val1,val2);
        fetch('gettable.php', {
          method: 'POST', // Specify the request method
          body: formData  // Pass the FormData object or any other data
        })
          .then(response => response.json())
          .then(data => {
            let div = document.getElementById('userfrnds');
            div.innerHTML="";
            data.forEach(message => {
              
              let messageElement = document.createElement('div');
              messageElement.className= 'chat';
              messageElement.innerHTML="<img src='https:/img.icons8.com/?size=100&id=LPk9CY756Am8&format=png&color=000000' alt='Contact' class='chat-pic'><div class='chat-info'><h3>"+message.tbnames+"</h3></div>";
              // messageElement.textContent = "<img src='https:/img.icons8.com/?size=100&id=LPk9CY756Am8&format=png&color=000000' alt='Contact' class='chat-pic'><div class='chat-info'><h3>"+message.tbnames+"</h3></div>";
              messageElement.onclick= function (){
                show(message.tbnames,a);
              };
              div.appendChild(messageElement);
              // div.appendChild('<br>');

              // console.log(message.tbnames);
            });
            
          })
          .catch(error => console.error('Error fetching messages:', error));
        }
        setInterval(fetchtables, 1000);
}
  function adduser(a){
    let alreadyfrnd=false;
    const formData = new FormData();
        formData.append('key1',currentuser);
        // console.log(val1,val2);
        fetch('gettable.php', {
          method: 'POST', // Specify the request method
          body: formData  // Pass the FormData object or any other data
        })
          .then(response => response.json())
          .then(data => {
            // let div = document.getElementById('userfrnds');
            // div.innerHTML="";
            data.forEach(message => {
              if(a==message.tbnames){
                alreadyfrnd=true;
                show(message.tbnames,currentuser);
              }
              
            });
            
          })
          .catch(error => console.error('Error fetching messages:', error));
          if(!alreadyfrnd){
            const formData = new FormData();
        formData.append('key1',currentuser);
        formData.append('key2',a);
        // console.log(val1,val2);
        fetch('addnewuser.php', {
          method: 'POST', // Specify the request method
          body: formData  // Pass the FormData object or any other data
        })
          // .then(response => response.json())
          // .then(data => {            
          // })
          // .catch(error => console.error('Error fetching messages:', error));
          }
        gettables(currentuser);
        cleardropdown();
  }