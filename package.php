<?php
$zipArchive = new ZipArchive();
$filename = 'DedicatedManager.zip';
@unlink($filename);

function recursiveBuildPackage($localPath, $archivePath, ZipArchive $zipArchive)
{
	$files = scandir($localPath);
	foreach($files as $file)
	{
		if(in_array($file, array('.', '..', '.git', $zipArchive->filename)))
		{
			continue;
		}
		if(is_dir($localPath.$file))
		{
			$newArchivePath = $archivePath.$file.'/';
			$newLocalPath = $localPath.$file.'/';
			$zipArchive->addEmptyDir($newArchivePath);
			printf("create folder %s\n\tas %s\n",$newLocalPath, $newArchivePath);
			recursiveBuildPackage($newLocalPath, $newArchivePath, $zipArchive);
		}
		else
		{
			printf("add %s\n\tas %s\n",$localPath.$file,$archivePath.$file);
			$zipArchive->addFile($localPath.$file, $archivePath.$file);
		}
	}
}

if($zipArchive->open($filename, ZipArchive::CREATE) == false)
{
	exit("cannot open <$filename>\n");
}
recursiveBuildPackage('./','DedicatedManager/', $zipArchive);
echo $zipArchive->numFiles.'|'.$zipArchive->getStatusString()."\n";
$zipArchive->close();
