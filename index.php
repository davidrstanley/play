<?php
    session_start();
    if(isset($_SESSION['fname'])){
        $username = $_SESSION['fname'];
        $user_id = $_SESSION['user_id'];
    }
?>

<!DOCTYPE html>
    <head>
        <?php include('inc/head.php'); ?>
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <title>Ultimate Workout Book</title>
    </head>

    <body>
        
            <div id="content">
                <div id="wrapper">
                    <header>
                        <h1>Ultimate Workout Book</h1>
                    </header>
                    <div class="login">
                        <?php
                            if(isset($username)){
                                ?>
                                    <p><a href="logout.php">Welcome <?php echo $username; ?></a></p>
                                    <p><a href="logout.php">Welcome <?php echo $user_id; ?></a></p>
                                <?php
                            } else {
                                ?>
                                    <input type="text" placeholder="Username" name="username">
                                    <input type="text" placeholder="Password" name="psw">
                                    <button type="submit">Login</button>
                                    <p><a href="signup.php">Sign Up</a></p>

                                <?php
                            }
                        
                        ?>
                    </div>
                    
                    <div class="muscle"></div>

                    <main> 
                        <h2 class="title">LISTEN / PUMP / LIVE</h2>

                        <div class="buttons">
                            <a class="button" href="beginner.php">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                Beginner
                            </a>
    
                            <a class="button" href="Intermediate.php">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                Intermediate
                            </a>
                
                            <a class="button" href="Insane.php">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                Insane
                            </a>

                        </div>
                        <div class="speech">

                            <h3 class="maintitle">Motivational Speech</h3>
                            <audio id="playme" controls>
                                <source class="audio" src="rise.mp3" type="audio/mpeg">
                            </audio>
                            <p>Today. Right now you are going to war.
                            You are going into war with your opponent
                            You are going to war with yourself.
                            You are not scared… You are prepared
                            You are not weak… You are a machine. A Freak.
                            Are you focused?!?….
                            
                            I AM FOCUSED
                            I AM FOCUSED
                            I AM FOCUSED
                            
                            Yes you are. You are focused and you will not lose sight of that.
                            Not today, not tomorrow, next week or next year.
                            Repeat after me.
                            Today is my day.
                            No one will get in the way of my dreams,
                            of my growth or my desire to be the VERY BEST in my chosen field.
                            Yes i said the best.
                            No one has the right to take that mantle from me.
                            I will sacrifice until i reach the very top.
                            No matter how hard it gets.
                            No matter how many times life beats me down.
                            I will get up EVERYTIME!
                            I will fight tooth and nail.
                            
                            Desire Drive Determination & Fire, they burn within me.
                            I WILL NOT BACK DOWN
                            I WILL NOT RELENT
                            When my body screams NO i will scream LOUDER…YES…
                            Give me more. push me harder
                            Because i know my courage lies in my HEART
                            My heart is stronger than my body.
                            No one has a stronger HEART a stronger mind
                            Send me 10,000 men and i will defeat them all with heart alone.
                            
                            I AM FOCUSED.
                            I HAVE THE HEART OF A LION
                            THE STRENGTH OF TEN & THE BLEEDING DESIRE OF A THOUSAND MEN.
                            
                            I am prepared for battle
                            I am prepared to soar
                            Come at me i dare you it’s TIME FOR WAR!
                            
                            Respect is not given it is earned. I don’t need an alarm clock
                            My goals wake me
                            My desire wakes me
                            My purpose wakes me
                            
                            I don’t need haters to fuel my fire
                            My PURPOSE is my fire
                            My FAMILY is my fire
                            My GREATNESS is my fire
                            
                            I don’t need others opinions
                            I have my own opinions
                            I have my own heart
                            I have my own DREAMS
                            
                            There’s nothing I can not be do or have!
                            
                            No one can tell me what I can or can’t do!
                            I decide what is possible for me
                            I decide what path I will choose
                            I will decide what sacrifices will be made
                            What people will walk with me
                            What people I must let go
                            I decide how big my goals are
                            How crazy they might seem to ordinary people
                            I am far from ORDINARY
                            
                            I don’t pick average anything!
                            I am anything but average!
                            I want more!
                            I will be more! Because I will give more!
                            More effort
                            More pain
                            More sacrifice
                            More heart
                            More courage and what comes next
                            MORE REWARD!
                            
                            There’s nothing I can not be do or have!
                            No one can tell me what I can or can’t do!
                            I decide what is possible for me
                            I decide what path I will choose
                            I will decide what sacrifices will be made
                            
                            Live your dreams
                            BE FEARLESS</p>
                        </div>
                    </main>


                        <nav id="menu-bar">
                            <div id="menu">
                                <div id="bar1" class="bar"></div>
                                <div id="bar2" class="bar"></div>
                                <div id="bar3" class="bar"></div>
                                <ul class="nav" id="nav">
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">Workout</a></li>
                                    <li><a href="#">Nutrition</a></li>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="custom.php">Custom Workout</a></li>
                                </ul>
                            </div>
                            <div class="menu-bg" id="menu-bg"></div>
                        </nav>  
                         

                      
                        <footer>
                            <h2>Workout Guides</h2>
                            <p class="footerp">This page is designed to help individuals maintain a healthy workout schedule.<br> The workouts range from beginner to extreme workouts. Alot of people find <br>themselves doing the exact same workouts
                                or similar workouts throughout an entire year. 
                            </p>
                            <p><i class="fas fa-phone"></i>828-828-828</p>
                            <p><i class="fas fa-envelope-square"></i>info@workouts.com</p>
                            <i class="fab fa-facebook"></i>
                            <i class="fab fa-twitter-square"></i>
                            <i class="fab fa-instagram"></i>
                        </footer>
                        <div class="message">
                            <div class="form">
                            <h3>Contact Us!</h3>
                            <form action="mailto:someone@example.com" method="post" enctype="text/plain">
                                E-mail:<br>
                                <input type="text" name="mail" style="background-color: #D3D3D3; width: 200px;"><br>
                                Comment:<br>
                                <input type="text" name="comment" size="50" class="inputsize" style="background-color: #D3D3D3;"><br><br>
                                <input type="submit" value="Send" style="background-color: #A9A9A9;">
                                <input type="reset" value="Reset" style="background-color: #A9A9A9;">
                                </form>
                            </div>
                        </div>
            </div>
        </div>
    </body>
</html>