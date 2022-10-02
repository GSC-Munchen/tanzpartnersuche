.. include:: ../Includes.txt

.. _known-problems:

==============
Known Problems
==============

The extension is feature complete. But still work in progress. Here are the highlights:

- backend module is not provided yet. In theory you could enter or edit profiles manually in the backend by adding a system folder pointing to the part of the TYPO3 database where the profiles are stored. But you have to know what to enter, and understand the source code. So for the moment it is definetely recommended to not change anything manually.
- more configuration possibilities have to be added. For the moment e.g. CSS file in /Resources/Public/CSS/custom.css has to be edited manually. Other things like mailaddress for admin has to be changed in the source code.
- Text has to be removed from Templates, Repository and Controller and to be fully exported to /Resources/Private/Language/locallang.xlf.
- locallang.xlf has to be enhanced to support other languages than only German.
- possibility to resend the verification code if expired after 48h.
- removing profiles from database and not only mark them as deleted.
- ...
