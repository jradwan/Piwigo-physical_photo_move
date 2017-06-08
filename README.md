# Physical Photo Move
[Piwigo](http://piwigo.org/) extension to move a photo (the actual file) from one physical album to another, preserving all metadata.

This is my first attempt at a plug-in for Piwigo! I don't use the [virtual albums](http://piwigo.org/doc/doku.php?id=user_documentation:albums_management) feature much as I prefer to have all of my photos stored in folders of my own hierarchical design rather than Piwigo's "./upload/year/month/day/randomfilename.jpg" structure used by the upload mechanism. The problem with using physical albums, however, is that I tend to occasionally re-organize and move a photo or video to a different folder. Then the next time I run Piwigo's synchronization process the file's original location is deleted from the database and re-added in the new folder location. This means all of the metadata associated with the item (tags, description, etc.) are lost and I have to enter them all again.

Physical Photo Move (PPM) is an attempt to alleviate this inconvenience by allowing an existing item in a physical folder (i.e., not in the upload folder and linked into a virtual album) to be moved to another folder and keep all the existing metadata.

- - -
## Usage

Instructions and screenshots forthcoming.

- - -
## To Do

- thumbnail management (delete old originals in source, create new ones for destination)
- link into Batch Manager (to move multiple photos at once)

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
