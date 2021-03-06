<!-- Msg Area -->
<div id="showMsg" class="container" style="display: none;">
    <div class="sixteen columns showError"></div>
</div>

<!-- Web Radio -->
<div class="container">
    <div class="sixteen columns">
        <div id="sdrPure"></div>
    </div>
</div>

<div class="container">
    <div class="sixteen columns">
        <button id="button">SEND DATA</button>
    </div>
    <hr>
</div>

<?php
handleSdrState();

function handleSdrState(){
    $userLevel = getUserLevel();
    if($userLevel >= 20){
        if(isset($_POST["moderatorChannel"]) or isset($_POST["stopChannel"])){
            showError("Wrong permissions");
            return;
        }
    }
    if($userLevel >= 10) {
        if(isset($_POST["stopChannel"])){
            showError("Wrong permissions");
            return;
        }
    }
    echo '<script src="../../js/sites/SdrManager.js"></script>';

//    $_SESSION["sdr"] = true; //In use
//    $_SESSION["sdr"] = false; //Init state
    unset($_SESSION["sdr"]); //TODO Just 4 test reasons
    if(isset($_SESSION["sdr"])){
        //Handle sdr already in use
    } else {
        //new connection
        if(isset($_POST["listenChannel"])){
            handleListenCommand();
        } else if(isset($_POST["moderatorChannel"])) {
            handleModeratorCommand();
        }else if(isset($_POST["stopChannel"])) {
            handleStopCommand();
        }
    }
}

function handleListenCommand(){
    //Get server location
    $host = getManagerIp();
    $port = getManagerPort();

    //Message to send
    $message = "listenChannel(".$_POST["listenChannel"].")";
    $result = sendMessageToSocket($host, $port, $message);
    $result = json_decode($result);

    if($result->state == "f"){
        showError($result->message);
    } else {
        setMidFrequency($_POST["midFreq"]);
        setDspPort((string)$result->port);
        $_SESSION["sdr"] = true;
    }
}

function handleModeratorCommand(){
    //Get server location
    $host = getManagerIp();
    $port = getManagerPort();

    //Message to send
    $message = "startChannel(".$_POST["moderatorChannel"].")";
    $result = sendMessageToSocket($host, $port, $message);
    $result = json_decode($result);

    if($result->state == "f"){
        showError($result->message);
    } else {
        setMidFrequency($_POST["midFreq"]);
        setDspPort((string)$result->port);
        $_SESSION["sdr"] = true;
    }
}

//TODO Function check (What to do after the channel has stopped?)
function handleStopCommand(){
    //Get server location
    $host = getManagerIp();
    $port = getManagerPort();

    //Message to send
    $message = "stopChannel(".$_POST["stopChannel"].")";
    $result = sendMessageToSocket($host, $port, $message);
    $result = json_decode($result);

    if($result->state == "f"){
        showError($result->message);
    } else {
        setMidFrequency($_POST["midFreq"]);
        setDspPort((string)$result->port);
        $_SESSION["sdr"] = true;
    }
}