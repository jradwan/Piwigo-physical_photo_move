# Physical Photo Move
PPM is a [Piwigo](http://piwigo.org/) extension to move a photo/video (file) or album (directory) from one physical album (directory) to another, preserving all metadata.

I don't use the [virtual albums](http://piwigo.org/doc/doku.php?id=user_documentation:albums_management) feature much as I prefer to have all of my photos stored in folders of my own hierarchical design rather than Piwigo's structure used by the upload mechanism (i.e., "./upload/year/month/day/randomfilename.jpg"). The problem with using physical albums, however, is that I tend to occasionally re-organize and move a photo, video, or album into a different folder. Then the next time I run Piwigo's synchronization process the file's original location is deleted from the database and re-added in the new folder location. This means all of the metadata associated with the item (tags, description, etc.) are lost and I have to enter them all again.

PPM (Physical Photo Move) is an attempt to alleviate this inconvenience by allowing an existing item (photo, video, or album) in a physical folder (i.e., not in the upload folder and linked into a virtual album) to be moved to another folder and keep all the existing metadata.

- - -
## Usage

After activating the plugin for your Piwigo site, there will be a "Move" tab in the "Edit" area for any item that is located in a physical album.

![UI screenshot](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-main-ui.jpg)
 
Select a destination album (only other physical albums are shown). When moving an album, that album and its sub-albums are hidden from the list (i.e., you can't move a parent folder into itself or one its children). Click "Move." With the simulation checkbox on, only informational messages are displayed (the item is not moved and the database is not updated). 

![debug screenshot](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-debug-info.jpg)

If everything in the source and destination looks good, turn off the simulation checkbox, select the destination album again, and click "Move" to move the item.

![move successful](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-moved.jpg)

If a file with the same name already exists in the destination, the source file will be renamed before it is moved and an informational message will be displayed:

![file renamed](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-rename.jpg)

Only the stored album (i.e., the folder location on disk) is changed, along with the corresponding information in the database. Any virtual albums linked to the photo will be unchanged. This includes any physical albums that the photo is associated with "virtually" but not physically stored in (linked albums). If the photo is being moved to a physical album that it is already linked to (see issue [#3](https://github.com/jradwan/Piwigo-physical_photo_move/issues/3)), a message will be displayed indicating the virtual link has been removed:

![merge](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-virtual-merge.jpg)

All previously generated representatives (thumbnails for non-image files) and derivates (resizes, thumbnails, etc. for images) are also moved to their proper destination directories.

PPM also supports Piwigo's Batch Manager for moving multiple items at a time. In global mode, select the "Move to physical album" action to show the destination album list:

![batch_mode](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-batch-ui.jpg)

Follow the same instructions above to batch move the items to the selected destination album. Multiple messages will be displayed showing the status of each file:

![batch_debug](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-batch-debug-info.jpg)

- - -
## Disclaimer

This is my first attempt at developing a plug-in for Piwigo! It was created to address a specific problem I was having with photo management and physical albums. Since PPM makes file system and database changes,  _please_ make sure you have a backup of your Piwigo folder structure and database before trying my plugin for the first time. No program is without bugs and while I've tested the plugin extensively myself, there's always the possibility for something to go wrong. Use simulation mode and check the debugging messages closely before allowing the plugin to make actual changes for the first time. If you find a problem, please open an issue [here](https://github.com/jradwan/Piwigo-physical_photo_move/issues) on Github or on the [Piwigo forums](http://piwigo.org/forum/) and let me know!

- - -
## To Do

- move an entire album (see issue [#4](https://github.com/jradwan/Piwigo-physical_photo_move/issues/4))

- - -
## Contact

Jeremy C. Radwan

- https://github.com/jradwan
- http://www.windracer.net/blog

- - -
## References

- [Piwigo](http://piwigo.org/)
- [Piwigo Extensions](http://piwigo.org/ext/)
- [Piwigo Plugin Tutorial](http://piwigo.org/doc/doku.php?id=dev:extensions:plugin_tutorial1)
