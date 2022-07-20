# Headless Container Support
First of all, this is WIP. 

## Install 
Add this Repo to your root composer.json 
```
"repositories": {
    "2": {
        "type": "vcs",
        "url": "git@github.com:Fanor51/headless-container-support.git"
    }
}
```
and add the dependency to your require block.
``
"fanor51/headless-container-support": "1.0.0"
``

Or just copy the File ``Classes/DataProcessing/HeadlessContainerProcessor.php`` in your Project but dont forget to change the namespace ;).

## Use 
Take a look into ``Example/TypoScript/Container/50_wrapper.typoscript`` there is an example on how to configure the container. 
The Special thing here is that we add a "new" data processor to the config. the processor is mainly the one from the container extension. Something was added only at the end

After this you need to overwrite the standard ``lib.content`` object with a where clause to exclude the "special" colPos´s from the container extension.
```
# PRELOAD EXTENSIONS SETTINGS
@import 'EXT:headless/Configuration/TypoScript/'

# Overwrite the default lib.content from the headless extension with added colPos where in YOUR Config Extension
# Add the colPos IDS from your Container Configuration to the where
lib.content = CONTENT_JSON
lib.content {
  table = tt_content
  select {
    orderBy = sorting
    where = colPos NOT IN (201,202,203)
  }
}
```

in this example we use the colPoses 201,202 and 203 for container contents which where setted in the container config under ``Configuration/TCA/Overrides/tt_content.php``.
An example for this you can find here: ``Example/TCA/Overrides/tt_content.php``

In the end it should look like this:

![image description](Docs/Assets/img.png)