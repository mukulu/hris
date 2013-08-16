Loggable behavioral extension for Doctrine2
============================================

**Loggable behavior tracks your record changes and is able to manage versions.**

Features:
---------

* Automatic storage of log entries in database
* ORM and ODM support using same listener
* Can be nested with other behaviors
* Objects can be reverted to previous versions
* Annotation, Yaml and Xml mapping support for extensions

* By the end, you'll see how Symfony2 can rescue you from mundane tasks and
  let you take back control of your code.

Setup and autoloading
-------------------------

Read the `documentation <http://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/annotations.md#em-setup>`
or check the `example <http://github.com/l3pp4rd/DoctrineExtensions/tree/master/example>` code on how to setup
and use the extensions in most optimized way.:

Loggable annotations:
* @Gedmo\Mapping\Annotation\Loggable(logEntryClass="my\class") this class annotation will use store logs to optionaly
  specified logEntryClass
* @Gedmo\Mapping\Annotation\Versioned tracks annotated property for changes


**Loggable username:**
In order to set the username, when adding the loggeable listener you need to set it this way::

    $loggableListener = new Gedmo\Loggable\LoggableListener;
    $loggableListener->setAnnotationReader($cachedAnnotationReader);
    $loggableListener->setUsername('admin');
    $evm->addEventSubscriber($loggableListener);

**Loggable Entity example:**

.. note::

      Loggable interface is not necessary, except in cases there you need to identify entity as being Loggable.
      The metadata is loaded only once when cache is active

::

    <?php
    namespace Entity;

    use Gedmo\Mapping\Annotation as Gedmo;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @Entity
     * @Gedmo\Loggable
     */
    class Article
    {
        /**
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @Gedmo\Versioned
         * @ORM\Column(name="title", type="string", length=8)
         */
        private $title;

        public function getId()
        {
            return $this->id;
        }

        public function setTitle($title)
        {
            $this->title = $title;
        }

        public function getTitle()
        {
            return $this->title;
        }
    }

