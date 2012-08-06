<?php

/**
 * Description of CreateMultiAppCommand
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class CreateMultiAppCommand extends CConsoleCommand
{

    public function actionIndex($path = null)
    {

        if (empty($path))
        {
            $this->usageError('Please define path parameter --path=...');
        }
        else
        {
            $sourceDir = realpath(dirname(__FILE__) . '/../views/multiapp');
            if ($sourceDir === false)
            {
                die("\nUnable to locate the source directory.\n");
            }
            $list = $this->buildFileList($sourceDir, $path);
            $this->copyFiles($list);
            $frameworkList = $this->buildFileList(YII_PATH, $path . '/framework');
            $this->copyFiles($frameworkList);
            $backvendorList = $this->buildFileList(dirname(__FILE__) . '/../../', $path . '/core/extensions/yii-backvendor');
            $this->copyFiles($backvendorList);
            $this->removeDirRecursively($path . '/core/extensions/yii-backvendor/.git');
            $this->removeDirRecursively($path . '/core/extensions/yii-backvendor/framework');
            unlink($path . '/core/extensions/yii-backvendor/.gitignore');
            @chmod($path . '/applications/webservice/assets', 0777);
            @chmod($path . '/applications/webservice/protected/runtime', 0777);
            @chmod($path . '/applications/backend/assets', 0777);
            @chmod($path . '/applications/backend/protected/runtime', 0777);
            @chmod($path . '/applications/images', 0777);
            echo "\nYour multiapplication has been created successfully under {$path}.\n";
        }
    }

    // php doesn't have a native function for recursive dir remove :(
    // copy-paste from http://lixlpixel.org/recursive_function/php/recursive_directory_delete/
    protected function removeDirRecursively($directory, $empty = FALSE)
    {
        if (substr($directory, -1) == '/')
        {
            $directory = substr($directory, 0, -1);
        }
        if (!file_exists($directory) || !is_dir($directory))
        {
            return FALSE;
        }
        elseif (is_readable($directory))
        {
            $handle = opendir($directory);
            while (FALSE !== ($item = readdir($handle)))
            {
                if ($item != '.' && $item != '..')
                {
                    $path = $directory . '/' . $item;
                    if (is_dir($path))
                    {
                        $this->removeDirRecursively($path);
                    }
                    else
                    {
                        unlink($path);
                    }
                }
            }
            closedir($handle);
            if ($empty == FALSE)
            {
                if (!rmdir($directory))
                {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function usageError($message)
    {
        echo "Error: $message\n\n";
        exit(1);
    }

}