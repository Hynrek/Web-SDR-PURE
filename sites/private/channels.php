<!-- Msg Area -->
<div id="showMsg" class="container" style="display: none;">
    <div class="sixteen columns showError"></div>
</div>

<div class="container">
    <div class="sixteen columns">
        <div id="" class="tagline">
            <h1>Channel overview</h1>
        </div>
<?php
connectToManager();

function connectToManager(){
    //Message to send
    $message = "getChannels()";

    //Get server location
    $host = getManagerIp();
    $port = getManagerPort();

    //Get Channels
    $result = sendMessageToSocket($host, $port, $message);
    //$result = '[{"freq":"24800000","state":"free"},{"freq":"24800000","state":"free"}]';

    //Decode JSON String
    $channelArray = json_decode($result);
    buildChannelContent($channelArray);
}

function buildChannelContent($channelArray){
    buildTableHead();
    echo '<tbody>';
    foreach($channelArray as $key=>$channel){
        $row = "<tr>";
        $row .= '<td>'. $key . '</td>';
        $row .= '<td>'. $channel->state . '</td>';
        $row .= '<td>'. $channel->freq . '</td>';
        $row .=  buildUserContent($key, $channel->freq);
        $row .= "</tr>";
        echo $row;
    }
    echo '</tbody>';
    echo '</table>';
}

function buildTableHead(){
    $table = '<table border="1" class="u-full-width">';
    $table .= '<thead>';
    $table .= '<tr>';
    $table .= '<th>Channel</th>';
    $table .= '<th>State</th>';
    $table .= '<th>Frequency</th>';
    $table .= buildUserHead();
    $table .= '</tr>';
    $table .= '</thead>';
    echo $table;
}

function buildUserHead(){
    $userHead = '';
    $userLevel = getUserLevel();
    if($userLevel <= 20){
        $userHead .= '<th>Listen</th>';
    }
    if($userLevel <= 10) {
        $userHead .= '<th>Connect</th>';
    }
    if($userLevel <= 1){
        $userHead .= '<th>Stop</th>';
    }
    return $userHead;
}

function buildUserContent($channelNr, $midFreq) {
    $action= getServer();
    $action .= "?site=sdr";
    $form = "<form class='borderlessForm' action='".$action."' method='post'>";
    $freq = '<input type="hidden" name="midFreq" value="'.$midFreq.'">';

    $userContent = "";
    $userLevel = getUserLevel();
    if($userLevel <= 20){
        $button = '<button type="submit" value="'.$channelNr.'" name="listenChannel">Listen</button>';
        $userContent .= '<td>'.$form.$button.$freq.'</form></td>';
    }
    if($userLevel <= 10) {
        $button = '<button type="submit" value="'.$channelNr.'" name="moderatorChannel">Connect</button>';
        $userContent .= '<td>'.$form.$button.$freq.'</form></td>';
    }
    if($userLevel <= 1){
        $button = '<button type="submit" value="'.$channelNr.'" name="stopChannel">Stop</button>';
        $userContent .= '<td>'.$form.$button.$freq.'</form></td>';
    }
    return $userContent;

}

?>

    </div>
</div>

