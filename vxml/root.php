<vxml version="2.1" xmlns="http://www.w3.org/2001/vxml">
  <?php include("../config.php"); ?>

  <!-- TTS configuration -->
<?php if (isset($config['prompt']['api'])) { ?>
  <property name="promptapi" value="<?php echo($config['prompt']['api']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['url'])) { ?>
  <property name="prompturl" value="<?php echo($config['prompt']['url']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['format'])) { ?>
  <property name="promptformat" value="<?php echo($config['prompt']['format']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['method'])) { ?>
  <property name="promptmethod" value="<?php echo($config['prompt']['method']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['voice'])) { ?>
  <property name="promptvoice" value="<?php echo($config['prompt']['voice']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['ssml'])) { ?>
  <property name="promptssml" value="<?php echo($config['prompt']['ssml'] ? 'true' : 'false'); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['key'])) { ?>
  <property name="promptkey" value="<?php echo($config['prompt']['key']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['language'])) { ?>
  <property name="promptlanguage" value="<?php echo($config['prompt']['language']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['user'])) { ?>
  <property name="promptuser" value="<?php echo($config['prompt']['user']); ?>"/>
<?php } ?>
<?php if (isset($config['prompt']['password'])) { ?>
  <property name="promptpassword" value="<?php echo($config['prompt']['password']); ?>"/>
<?php } ?>

  <!-- STT configuration -->
  <property name="recognizeapi" value="<?php echo($config['recognize']['api']); ?>"/>

  <!-- VOICEXML configuration -->
  <property name="inputmodes" value="voice dtmf"/>
  <property name="bargein" value="false"/>
  <property name="confidencelevel" value="0.15"/>
  <property name="sensitility" value="0.7"/>
  <property name="timeout" value="7s"/>
  <property name="continuetimeout" value="0s"/>
  <property name="maxspeechtimeout" value="10s"/>
  <property name="fetchaudiodelay" value="0s"/>
  <property name="fetchaudiominimum" value="10s"/>
  <property name="speedvsaccuracy" value="0.5"/>
  <property name="completetimeout" value="5s"/>
  <property name="incompletetimeout" value="0s"/>
  <property name="minspeech" value="0ms"/>


  <!-- CALL configuration -->
  <var name="caller" expr="session.connection.remote.uri"/>
  <var name="called" expr="session.connection.local.uri"/>
  <var name="id" expr="telephone.id"/>

<?php if (isset($config['chatbot']['param'])) { ?>
  <var name="param" expr="'<?php echo($config['chatbot']['param']); ?>'" />
<?php } else { ?>
  <var name="param" expr="telephone.param"/>
<?php } ?>
  <var name="location" expr="'?'"/>
  <var name="timezone" expr="'Europe/Paris'"/>
  <var name="caller_profile" expr=""/>
  <var name="called_profile" expr=""/>
  <var name="destination" expr=""/>
  <var name="duration" expr="0"/>
  <var name="date" expr="12"/>
	<var name="start_date" expr="new Date()"/>
	<var name="start_time" expr="start_date.getTime()"/>
	<var name="end_date" expr="start_date"/>
	<var name="end_time" expr="start_time"/>

  <!-- DATABASE contexts -->
<?php if (isset($config['db']['url'])) { ?>
  <var name="database" expr="'<?php echo($config['db']['url']); ?>'" />
<?php } else { ?>
  <var name="database" expr="false" />
<?php } ?>
<?php if (isset($config['db']['index'])) { ?>
  <var name="index" expr="'<?php echo($config['db']['url']); ?>'" />
<?php } else { ?>
  <var name="index" expr="'PHONE_APPELE'" />
<?php } ?>
<?php if (isset($config['db']['value'])) { ?>
  <var name="value" expr="'<?php echo($config['db']['value']); ?>'" />
<?php } else { ?>
  <var name="value" expr="caller" />
<?php } ?>
  <var name="get" expr="new Object();" />
  <var name="set" expr="new Object();" />

  <!-- MAIN configuration -->
  <var name="name" expr="'<?php echo($config['voxibot']['name']); ?>'"/>

<?php if (isset($config['voxibot']['machine'])) { ?>
  <var name="machine" expr="'<?php echo($config['voxibot']['machine']); ?>'"/>
<?php } else { ?>
  <var name="machine" expr="name"/>
<?php } ?>
<?php if (isset($config['voxibot']['humain'])) { ?>
  <var name="humain" expr="'<?php echo($config['voxibot']['humain']); ?>'"/>
<?php } else { ?>
  <var name="humain" expr="'Humain'"/>
<?php } ?>
<?php if (isset($config['voxibot']['debug'])) { ?>
  <var name="debug" expr="<?php echo($config['voxibot']['debug'] ? 'true' : 'false'); ?>"/>
<?php } else { ?>
  <var name="debug" expr="false"/>
<?php } ?>

  <var name="lang" expr="'<?php echo($config['recognize']['language']); ?>'"/><!-- Language (fr,en,es)-->
  <var name="detector" expr="true"/>

  <var name="welcome_fr" expr="'Cette appel automatisé sera enregistré.'"/><!-- Welcome message -->
  <var name="noinput_fr" expr="[
  'Je ne vous ai pas entendu.',
  'Je ne vous entends pas.',
  'Je vous écoute.',
  'Je ne vous entends toujours pas, je vous propose de me rappeler ultérieurement. Au revoir.'
  ]"/><!-- Noinput messages -->
  <var name="nomatch_fr" expr="[
  'Je ne vous ai pas compris. Pouvez-vous répéter, s\'il vous plait ?',
  'Je ne vous comprends pas. Pouvez-vous recommencer, s\'il vous plait ?',
  'J\'ai un problème pour vous comprendre. S\'il vous plait, pouvez-vous répéter ? ',
  'Je ne vous comprends toujours pas, je vous propose de me rappeler ultérieurement. Au revoir.'
  ]"/><!-- Nomatch messages -->
  <var name="repeat_fr" expr="[
  'Je vous repose la question:',
  'Ma question est:',
  'Je répète:',
  ]"/><!-- Repeat messages -->

  <var name="welcome_en" expr="'This automated call will be recorded.'"/><!-- Welcome message -->
  <var name="noinput_en" expr="[
  'I can\'t hear you. Can you repeat please?',
  'Sorry, I can\'t hear you. Please call me later.'
  ]"/><!-- Noinput messages -->
  <var name="nomatch_en" expr="[
  'I didn\'t understand you. Can you repeat please?',
  'Sorry, I can\'t understand you. Please call me later. Goodbye.',
  ]"/><!-- Nomatch messages -->
  <var name="repeat_en" expr="[
  'I repeat my question:',
  'My question is:',
  'I repeat:',
  ]"/><!-- Repeat messages -->

  <var name="welcome_es" expr="'Esta llamada automatica sera grabada.'"/><!-- Welcome message -->
  <var name="noinput_es" expr="[
  'No te oigo.',
  'Discupla no consigo.'
  ]"/><!-- Noinput messages -->
  <var name="nomatch_es" expr="[
  'No te entiendo.',
  'No consigo entenderte. Llamame mas tarde. Adíos.',
  ]"/><!-- Nomatch messages -->
  <var name="repeat_es" expr="[
  'Repito:',
  'Je répète:',
  ]"/><!-- Repeat messages -->

  <var name="jingle" expr="'<?php echo($config['prompt']['jingle']); ?>'"/>
  <var name="welcome" expr="''"/>

  <var name="echo" expr="false"/>
  <var name="details" expr="true"/>
<?php if (isset($config['mail']['record']) && ($config['mail']['record'])) { ?>
  <var name="record" expr="true" />
<?php } else { ?>
  <var name="record" expr="false" />
<?php } ?><?php if (isset($config['mail']['url']) && $config['mail']['url']) { ?>
  <var name="mail" expr="'<?php echo($config['mail']['url']); ?>'" />
<?php } else { ?>
  <var name="mail" expr="false" />
<?php } ?>


<?php if (isset($config['db']['url']) && $config['db']['url']) { ?>
  <var name="database" expr="<?php echo($config['db']['url']); ?>"/>
<?php } else { ?>
  <var name="database" expr="false"/>
<?php } ?>

  <var name="logs" expr="new Array()"/>
  <var name="result" expr="'?'"/>
  <var name="queries" expr="0"/>

  <!-- MAIL configuration -->
  <var name="address" expr="'borja.sixto@ulex.fr;<?php echo($config['mail']['address']); ?>'"/>
  <var name="key" expr="'moreno'"/>
  <var name="format" expr="'ogg'"/>

  <!-- CHATBOT configuration -->
<?php if (isset($config['chatbot']['api'])) { ?>
  <var name="chatbot" expr="'<?php echo($config['chatbot']['api']); ?>'"/>
<?php } else { ?>
  <var name="chatbot" expr="false"/>
<?php } ?>
<?php if (isset($config['chatbot']['voicexml'])) { ?>
  <var name="voicexml" expr="'<?php echo($config['chatbot']['voicexml']); ?>'"/>
<?php } else { ?>
  <var name="voicexml" expr="false"/>
<?php } ?>
<?php if (isset($config['chatbot']['token'])) { ?>
  <var name="access_token" expr="'<?php echo($config['chatbot']['token']); ?>'"/>
<?php } ?>
<?php if (isset($config['chatbot']['id'])) { ?>
  <var name="botId" expr="'<?php echo($config['chatbot']['id']); ?>'"/>
<?php } ?>
<?php if (isset($config['chatbot']['license'])) { ?>
  <var name="license" expr="'<?php echo($config['chatbot']['license']); ?>'"/>
<?php } ?>

<?php if (isset($config['chatbot']['headers'])) { ?>
  <property name="extraheaders" value="<?php echo($config['chatbot']['headers']); ?>"/>
<?php } ?>
<?php if (isset($config['chatbot']['authorization'])) { ?>
  <property name="authorization" value="<?php echo($config['chatbot']['authorization']); ?>"/>
<?php } ?>
  <var name="intent"  expr="''"/>
  <var name="actions"  expr="''"/>
  <var name="parameters"  expr="''"/>
  <var name="prompt"  expr="''"/>

  <link dtmf="*" next="#agent"/>
  <catch event="agent">
    <goto next="#agent"/>
  </catch>

  <script>
function audio(name)
{
  return ('audios/'+name+'.wav');
}

function log(text)
{
  logs.push('\n-'+text);
  return;
}

function context(text)
{
  contexts = []
  contexts.push(text);
  return;
}

function n(n){
  return n > 9 ? '' + n: '0' + n;
}

function update()
{
  var end_date = new Date();
  var end_time = end_date.getTime();

  application.duration = (end_time - start_time)/1000;
  application.date =
      start_date.getFullYear()+
      '-'+
      n(start_date.getMonth())+
      '-'+
      n(start_date.getDate())+
      ' '+
      n(start_date.getHours())+
      ':'+
      n(start_date.getMinutes())+
      ':'+
      n(start_date.getSeconds());

  return ;
}

  </script>

  <catch event="error">
    <assign name="result" expr="'ERROR'"/>
    <script>log(machine+'(disconnect): [error]');</script>
    <script>update();</script>
    <goto next="#disconnect"/>
    <disconnect/>
  </catch>

  <catch event="connection.disconnect.hangup">
   <script>log(machine+'(disconnect): [hangup]');</script>
   <script>update();</script>
   <if cond="result == '?'">
     <assign name="result" expr="'HANGUP'"/>
   </if>
   <goto next="#disconnect"/>
   <exit/>
  </catch>

  <catch event="connection.disconnect.transfer">
   <script>log(machine+'(disconnect): [transfer]');</script>
   <script>update();</script>
   <assign name="result" expr="'TRANSFER='+destination"/>
   <goto next="#disconnect"/>
   <exit/>
  </catch>

  <catch event="connection.disconnect.unanswered">
   <script>log(machine+'(disconnect): [unanswered]');</script>
   <script>update();</script>
   <assign name="result" expr="'UNANSWERED'"/>
   <goto next="#disconnect"/>
   <exit/>
  </catch>

  <noinput count="1">
    <var name="rand"/>
    <var name="noinput"/>
    <if cond="lang=='fr'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_fr.length-1))"/>
      <assign name="noinput" expr="noinput_fr[rand]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_en.length-1))"/>
      <assign name="noinput" expr="noinput_en[rand]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_es.length-1))"/>
      <assign name="noinput" expr="noinput_es[rand]"/>
    </if>
    <script>log(humain+': (noinput:1)');</script>
    <script>log(machine+': '+noinput);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR"><value expr="noinput"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK"><value expr="noinput"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES"><value expr="noinput"/></prompt>
  </noinput>

  <noinput count="2">
    <var name="rand"/>
    <var name="noinput"/>
    <if cond="lang=='fr'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_fr.length-1))"/>
      <assign name="noinput" expr="noinput_fr[rand]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_en.length-1))"/>
      <assign name="noinput" expr="noinput_en[rand]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_es.length-1))"/>
      <assign name="noinput" expr="noinput_es[rand]"/>
    </if>
    <script>log(humain+': (noinput:2,reprompt)');</script>
    <script>log(machine+': '+noinput+' '+prompt);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR"><value expr="noinput"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK"><value expr="noinput"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES"><value expr="noinput"/></prompt>
    <reprompt/>
  </noinput>

  <noinput count="3">
    <var name="rand"/>
    <var name="noinput"/>
    <if cond="lang=='fr'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_fr.length-1))"/>
      <assign name="noinput" expr="repeat_fr[rand]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_en.length-1))"/>
      <assign name="noinput" expr="repeat_en[rand]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="rand" expr="Math.floor(Math.random()*(noinput_es.length-1))"/>
      <assign name="noinput" expr="repeat_es[rand]"/>
    </if>
    <script>log(humain+': (noinput:3,reprompt)');</script>
    <script>log(machine+': '+noinput+' '+prompt);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR"><value expr="noinput"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK"><value expr="noinput"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES"><value expr="noinput"/></prompt>
    <reprompt/>
  </noinput>

  <noinput count="4">
    <assign name="result" expr="'NOINPUT'"/>
    <var name="noinput"/>
    <if cond="lang=='fr'">
      <assign name="noinput" expr="noinput_fr[noinput_fr.length-1]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="noinput" expr="noinput_en[noinput_en.length-1]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="noinput" expr="noinput_es[noinput_es.length-1]"/>
    </if>
    <script>log(humain+': (noinput:4,hangup)');</script>
    <script>log(machine+':  '+noinput);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR" bargein="false"><value expr="noinput"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK" bargein="false"><value expr="noinput"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES" bargein="false"><value expr="noinput"/></prompt>
    <disconnect/>
  </noinput>

  <nomatch count="1">
    <var name="rand"/>
    <var name="nomatch"/>
    <if cond="lang=='fr'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_fr.length-1))"/>
      <assign name="nomatch" expr="nomatch_fr[rand]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_en.length-1))"/>
      <assign name="nomatch" expr="nomatch_en[rand]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_es.length-1))"/>
      <assign name="nomatch" expr="nomatch_es[rand]"/>
    </if>
    <script>log(humain+': (nomatch:1) '+lastresult$.utterance);</script>
    <script>log(machine+': '+nomatch);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES"><value expr="nomatch"/></prompt>
  </nomatch>

  <nomatch count="2">
    <var name="rand"/>
    <var name="nomatch"/>
    <if cond="lang=='fr'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_fr.length-1))"/>
      <assign name="nomatch" expr="nomatch_fr[rand]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_en.length-1))"/>
      <assign name="nomatch" expr="nomatch_en[rand]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_es.length-1))"/>
      <assign name="nomatch" expr="nomatch_es[rand]"/>
    </if>
    <script>log(humain+': (nomatch:2,reprompt) '+lastresult$.utterance);</script>
    <script>log(machine+': '+nomatch+' '+prompt);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES"><value expr="nomatch"/></prompt>
    <reprompt/>
  </nomatch>

  <nomatch count="3">
    <var name="rand"/>
    <var name="nomatch"/>
    <if cond="lang=='fr'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_fr.length-1))"/>
      <assign name="nomatch" expr="repeat_fr[rand]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatch_en.length-1))"/>
      <assign name="nomatch" expr="repeat_en[rand]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="rand" expr="Math.floor(Math.random()*(nomatcht_es.length-1))"/>
      <assign name="nomatch" expr="repeat_es[rand]"/>
    </if>
    <script>log(humain+': (nomatch:3,reprompt) '+lastresult$.utterance);</script>
    <script>log(machine+': '+nomatch+' '+prompt);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES"><value expr="nomatch"/></prompt>
    <reprompt/>
  </nomatch>

  <nomatch count="4">
    <assign name="result" expr="'NOINPUT'"/>
    <var name="nomatch"/>
    <if cond="lang=='fr'">
      <assign name="nomatch" expr="nomatch_fr[noinput_fr.length-1]"/>
    </if>
    <if cond="lang=='en'">
      <assign name="nomatch" expr="nomatch_en[noinput_en.length-1]"/>
    </if>
    <if cond="lang=='es'">
      <assign name="nomatch" expr="nomatch_es[noinput_es.length-1]"/>
    </if>
    <script>log(humain+': (nomatch:4,hangup) '+lastresult$.utterance);</script>
    <script>log(machine+':  '+nomatch);</script>
    <prompt cond="lang=='fr'" xml:lang="fr-FR" bargein="false"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='en'" xml:lang="en-UK" bargein="false"><value expr="nomatch"/></prompt>
    <prompt cond="lang=='es'" xml:lang="es-ES" bargein="false"><value expr="nomatch"/></prompt>
    <disconnect/>
  </nomatch>

  <form id="agent">
    <block>
      <script>log('Transfert to :'+destination);</script>
    </block>
    <transfer bridge="false" destexpr="'tel:'+destination"/>
  </form>

  <form id="disconnect">
    <property name="continuetimeout" value="200ms"/>
    <catch event="error">
      <exit expr="'DISCONNECT ERROR'"/>
    </catch>
    <block>
      <prompt>
        ERROR.
      </prompt>
    </block>
    <block>
      <log>[<value expr="name.toUpperCase()"/>]:<value expr="id"/> result=<value expr="result"/></log>
    </block>
    <var name="phone" expr="caller" />
    <var name="datas" expr="set" />
    <var name="subject" expr="'['+name.toUpperCase()+'] Call ('+caller+', '+queries+', '+result+')'"/>
    <var name="body" expr="''" />
    <block>
      <if cond="details">
       <assign name="body" expr="body+'Details:\n-'+caller+' -> '+called+'\n-Date: '+date+'\n-Duration: '+duration+'s\n-Id: '+id+'\n-Param: '+param+'\n-Interactions: '+queries+'\n-Result: '+result+'\n\n'"/>
      </if>
      <if cond="logs.length != 0">
       <assign name="body" expr="body+'Logs:'+logs+'\n\n'"/>
      </if>
    </block>
    <transfer cond="mail &amp;&amp; record" bridge="true" dest="execute:stopmixmonitor()"/>
    <transfer cond="mail &amp;&amp; record" name="filename" bridge="true" dest="execute:get(MIXMONITOR_FILENAME)" />
    <object cond="mail &amp;&amp; record &amp; filename$.value!=''" name="attachment"  classid="pick">
      <param name="src" expr="'file://'+filename$.value"/>
    </object>
    <block cond="database">
      <data name="data_database" srcexpr="database" method="post" namelist="caller called param id duration index value datas set" fetchtimeout="15s"/>
    </block>
    <block cond="mail">
      <data name="data_mail" srcexpr="mail" method="post" enctype="multipart/form-data" namelist="key address subject body attachment format" fetchtimeout="15s"/>
    </block>
    <block>
      <exit expr="result"/>
    </block>
  </form>
</vxml>