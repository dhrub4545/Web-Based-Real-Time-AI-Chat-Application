<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  header("location: index.php");
  exit;
}
?>
<?php
$server = "localhost";
$username = "root";
$password = "";
$database = $_SESSION['username'];
$globaltemp = $_SESSION['username'];
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
  die("Error" . mysqli_connect_error());
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="sc.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- Bootstrap CSS -->
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" -->
    <!-- integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
 
  <link rel="stylesheet" href="stylechatapp.css">
  <!-- <link rel="stylesheet" href="style.css"> -->
  <script>
    
    let idd;
    let temp = "";
    let db="";
  
    function fetch_data_fun(val1, val2) {
      let userScrolledUp = false;
      clearInterval(idd);
      function fetchMessages() {
        const formData = new FormData();
        formData.append('key1', val1);
        formData.append('key2', val2);
        // console.log(val1,val2);
        fetch('realtime.php', {
          method: 'POST', // Specify the request method
          body: formData  // Pass the FormData object or any other data
        })
          .then(response => response.json())
          .then(data => {
            let messagesDiv = document.getElementById('data');
            const previousScrollTop = messagesDiv.scrollTop;
            messagesDiv.innerHTML = ''; // Clear existing messages

            // Display the new messages
            data.forEach(message => {
              
              let messageElement = document.createElement('div');
              messageElement.innerHTML =  message.message;
              if(message.action === '1'){
                messageElement.className=" message sent";
              }else{
                messageElement.className=" message received";

              }
              messagesDiv.appendChild(messageElement);
            });
            if (!userScrolledUp || previousScrollTop === messagesDiv.scrollHeight - messagesDiv.clientHeight) {
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
      }
            // Scroll to the bottom
            // messagesDiv.scrollTop = messagesDiv.scrollHeight;
          })
          .catch(error => console.error('Error fetching messages:', error));
      }
      // document.createElement('div').scrollTop=document.createElement('div').scrollHeight;
      // Call the function to fetch messages every second
      document.getElementById('data').addEventListener('scroll', () => {
       const messagesDiv = document.getElementById('data');
  // Check if user has scrolled up
  userScrolledUp = messagesDiv.scrollTop < messagesDiv.scrollHeight - messagesDiv.clientHeight;
});
      if(idd){
        clearInterval(idd);
      }
      idd = setInterval(fetchMessages, 1000);
    }
      //function to send message
      // function sendmessage(){
      //   let message = document.getElementById('messageInput').value;
      //   // console.log(message,db,temp);
      //   if(temp==='chat_with_ai'){
          
      //     let fD = new FormData();
      //       fD.append('message', message);
      //       fD.append('k1', db);
      //       fD.append('k2', temp);
      //       fetch('chat_ai_send.php', {
      //     method: 'POST', // Specify the request method
      //     body: fD  // Pass the FormData object or any other data
      //   });
      //     const response =  fetch('http://127.0.0.1:5000/chat', {
      //           method: 'POST',
      //           headers: {
      //               'Content-Type': 'application/json',
      //           },
      //           body: JSON.stringify({ message: message }),
      //       });

      //       const data =  response.json();
      //       if (response.ok) {
      //           // responseDiv.textContent = data.response;
      //           let fD = new FormData();
      //       fD.append('message', data.response);
      //       fD.append('k1', db);
      //       fD.append('k2', temp);
      //       fetch('chat_ai_receive.php', {
      //     method: 'POST', // Specify the request method
      //     body: fD  // Pass the FormData object or any other data
      //   })
      //       } else {
      //           console.log('Error: ${data.error}');
      //       } 
          
      //   }else{
      //   let fD = new FormData();
      //       fD.append('message', message);
      //       fD.append('k1', db);
      //       fD.append('k2', temp);
      //       fetch('sendmessage.php', {
      //     method: 'POST', // Specify the request method
      //     body: fD  // Pass the FormData object or any other data
      //   })}
      //   document.getElementById('messageInput').value="";
      //     // .then(response => response.json())
          
      //     // .catch(error => console.error('Error fetching messages:', error));
      //       // fetch('sendmessage.php', {
      //       //     method: 'POST',
      //       //     body: fD
      //       // })
      //       // .then(response => response.text())
      //       // .then(data => {
      //       // })
      //       // .catch(error => console.error('Error submitting message:', error));
      // }
      // document.getElementById('messageForm').onsubmit = function(event) {
      //       event.preventDefault();

            
      //   };
      async function sendmessage() {
    let message = document.getElementById('messageInput').value;

    if (!message.trim()) {
        console.error('Message cannot be empty.');
        return;
    }

    if (temp === 'chat_with_ai') {
        try {
            // Send the user's message to the PHP backend for storage
            let storeUserMessage = new FormData();
            storeUserMessage.append('message', message);
            storeUserMessage.append('k1', db);
            storeUserMessage.append('k2', temp);

            await fetch('chat_ai_send.php', {
                method: 'POST',
                body: storeUserMessage,
            });

            // Send the message to the AI backend
            let response = await fetch('http://127.0.0.1:5000/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message }),
            });

            if (!response.ok) {
                throw new Error(`AI server error: ${response.statusText}`);
            }

            let data = await response.json();

            // Store AI response in the PHP backend
            let storeAIResponse = new FormData();
            storeAIResponse.append('message', data.response);
            storeAIResponse.append('k1', db);
            storeAIResponse.append('k2', temp);

            await fetch('chat_ai_receive.php', {
                method: 'POST',
                body: storeAIResponse,
            });
        } catch (error) {
            console.error('Error interacting with AI backend:', error);
        }
    } else {
        // For regular chat
        try {
            let regularChatData = new FormData();
            regularChatData.append('message', message);
            regularChatData.append('k1', db);
            regularChatData.append('k2', temp);

            await fetch('sendmessage.php', {
                method: 'POST',
                body: regularChatData,
            });
        } catch (error) {
            console.error('Error sending regular message:', error);
        }
    }

    // Clear the input field
    document.getElementById('messageInput').value = '';
}

    
  </script>
    <?php echo "<script> db='$globaltemp'</script>" ?>
  <script>
    gettables('<?php echo $_SESSION['username'] ?>');
    function show(a, b) {
      //  console.log(b,a);
      document.getElementById("contactname").innerText=a;
      fetch_data_fun(b, a);
      temp=a;
      // message_send();
    }
  </script>

  <title>Welcome - <?php $_SESSION['username'] ?></title>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="profile-section">
        <img src="https://img.icons8.com/?size=100&id=LPk9CY756Am8&format=png&color=000000" alt="Profile" class="profile-pic">
        <h2><?php echo $_SESSION['username'] ?></h2>
      </div>
      <div class="search-bar" onmouseleave="cleardropdown()">
      <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction('<?php echo $globaltemp; ?>')">
      
      <div id="myDropdown" class="dropdown-content">
    </div>
      </div>
      
  
      <div class="chat-list">
        <div id="userfrnds">
  
  </div> 
        <!-- <div class="chat" >
          <img src="https://img.icons8.com/?size=100&id=LPk9CY756Am8&format=png&color=000000" alt="Contact" class="chat-pic">
          <div class="chat-info">
            <h3>Contact Name</h3>
            <p>Last message preview...</p>
          </div>
        </div> -->
        <!-- Repeat for other chats -->
      </div>
    </div>

    <!-- Chat Section -->
    <div class="chat-section">
      <div class="chat-header">
        <img src="https://img.icons8.com/?size=100&id=LPk9CY756Am8&format=png&color=000000" alt="Contact" class="chat-header-pic">
        <h2 id="contactname" ><?php echo $_SESSION['username'] ?></h2>
      </div>
      <div class="chat-messages" id="data">
      </div>
      
      <div class="message-input">
      <!-- <form></form> -->
      <input type="text" id="messageInput" name="message"  required placeholder="Enter your message">
      <button onclick="sendmessage()">Send</button>
      </div>
    </div>
  </div>

<!-- <div id="data" >
  
</div> -->
<!-- <div id="form">
  <div>
        <input type="text" id="messageInput" name="message" required placeholder="Enter your message">
        <button onclick="sendmessage()">Send</button>
  </div> -->
 
  <!-- <p><a href="/chatapp/logout.php">log out</a></p> -->
<!-- <script>message_send()</script> -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>



  </body>

</html>