async function register(){
  const data = {
    login: document.getElementById('login').value,
    password: document.getElementById('password').value
  }
  
  try {
    const response = await fetch('auth.php',{
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data)
    });

    const res = await response.json();
  } catch (error) {
    console.error("Error", error);
  }
}

document.getElementById('register-btn').addEventListener('click', function(event){
  event.preventDefault();
  register();
});
