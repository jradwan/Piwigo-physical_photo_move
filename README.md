# Physical Photo Move
[Piwigo](http://piwigo.org/) extension to move a photo (the actual file) from one physical album to another, preserving all metadata.

This is my first attempt at a plug-in for Piwigo! I don't use the [virtual albums](http://piwigo.org/doc/doku.php?id=user_documentation:albums_management) feature much as I prefer to have all of my photos stored in folders of my own hierarchical design rather than Piwigo's "./upload/year/month/day/randomfilename.jpg" structure used by the upload mechanism. The problem with using physical albums, however, is that I tend to occasionally re-organize and move a photo or video to a different folder. Then the next time I run Piwigo's synchronization process the file's original location is deleted from the database and re-added in the new folder location. This means all of the metadata associated with the item (tags, description, etc.) are lost and I have to enter them all again.

Physical Photo Move (PPM) is an attempt to alleviate this inconvenience by allowing an existing item in a physical folder (i.e., not in the upload folder and linked into a virtual album) to be moved to another folder and keep all the existing metadata.

- - -
## Usage

After activating the plugin for your Piwigo site, there will be a "Move" tab in the "Edit Photo" area for any item that is location in a physical album.

![UI screenshot](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-main-ui.jpg)
 
Select a destination album (only other physical albums are shown) and click "Move." With the simulation checkbox on, only informational messages are displayed (the file is not moved and the database is not updated). 

![debug screenshot](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-debug-info.jpg)

If everything in the source and destination looks good, turn off the simulation checkbox, select the destination album again, and click "Move" to move the photo.

![move successful](https://github.com/jradwan/Piwigo-physical_photo_move/raw/master/resources/ppm-moved.jpg)

_Note:_ any virtual albums linked to the photo will be unchanged. This includes any physical albums that the photo is associated with "virtually" but not physically stored in (_unless_ the photo is being moved to a physical album that it is already virtually linked to (see issue [#3](https://github.com/jradwan/Piwigo-physical_photo_move/issues/3))). Only the stored album (i.e., the folder location on disk) is changed, along with the corresponding information in the database.

- - -
## To Do

- link into Batch Manager (to move multiple photos at once) (issue [#2](https://github.com/jradwan/Piwigo-physical_photo_move/issues/2))

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
