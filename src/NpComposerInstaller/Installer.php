<?php
namespace NpComposerInstaller;
/*
 * 
 * 
 * @copyright Copyright (c) 2013-2013 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Description of Installer
 *  Composer custom installer 
 *  @see http://getcomposer.org/doc/articles/custom-installers.md
 * 
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class Installer extends LibraryInstaller
{
    protected $init;
    
    protected $supportedLocalNs = 'np-local';
    
    protected $supportedLocalTypes = ['module', 'public', 'config', 'data'];
    
    protected $typeToDir;
    
    protected $typeDelimiter = "-";
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $this->initLocalDirs();
        $dir = '';
        $extra = $package->getExtra();
        /* @var $type string */
        $type = substr($package->getType(), strlen($this->supportedLocalNs) + 1);
        $name = $package->getPrettyName();
        
        $this->initLocalDirs();
        
        if (isset($this->typeToDir[$type])) {
            $dir .= $this->typeToDir[$type] . DIRECTORY_SEPARATOR ;
        }
        elseif (in_array($type, $this->supportedLocalTypes)) {
            $dir .= $type . DIRECTORY_SEPARATOR ;
        }
        else {
            $dir .= $this->vendorDir . DIRECTORY_SEPARATOR ;
        }
        
        $dir .= substr($name, strpos($name, '/') + 1);
        
        if ($extra) {
            if (isset($extra['target_dir'])) {
                $dir .= DIRECTORY_SEPARATOR  . $extra['target_dir'] ;
            }
        }
        // 

        return $dir;
    }
    
    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        if (0 !== strpos($packageType, $this->supportedLocalNs)) {
            return false;
        }
        
        $type = substr($packageType, strlen($this->supportedLocalNs) + 1);
        
        if (!in_array($type, $this->supportedLocalTypes)) {
            return false;
        }
        
        return true;
    }
    
    protected function initLocalDirs()
    {
        if ($this->init) {
            return true;
        }
        $localDirs = $this->composer->getConfig()->get('local_dirs');
        
        if (is_array($localDirs)) {
            foreach ($localDirs as $type => $dir) {
                if (! in_array($type, $this->supportedLocalTypes)) {
                    continue;
                }

                if (! is_string($dir)) {
                    continue;
                }

                $this->typeToDir[$type] = $dir;
            }
        }
            
        $this->init = true;
    }
}
