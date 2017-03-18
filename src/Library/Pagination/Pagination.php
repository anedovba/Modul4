<?php
namespace Library\Pagination;
class Pagination implements \Iterator
{
    public $buttons = array();
    private $position = 0;
    private $itemsCount;
    private $itemsPerPage;
    private $currentPage;


//    public function __construct(Array $options = array('itemsCount' => 257, 'itemsPerPage' => 10, 'currentPage' => 1))

    public function __construct($itemsCount,$itemsPerPage, $currentPage)
    {
        $this->itemsCount=$itemsCount;
        $this->itemsPerPage=$itemsPerPage;
        $this->currentPage=$currentPage;

        /** @var int $currentPage */
        if (!$this->currentPage) {
            return;
        }
        /** @var int $pagesCount
         *  @var int $itemsCount
         *  @var int $itemsPerPage
         */
        $pagesCount = ceil($this->itemsCount / $this->itemsPerPage);
        if ($pagesCount == 1) {
            return;
        }
        /** @var int $currentPage */
        if ($this->currentPage > $pagesCount) {
            $this->currentPage = $pagesCount;
        }
        $this->buttons[] = new Button(1, $this->currentPage > 1, 'Begin');
        $this->buttons[] = new Button($this->currentPage - 1, $this->currentPage > 1, 'Previous');
        for ($i = 1; $i <= $pagesCount; $i++) {
            $current=false;
            $active = $this->currentPage != $i;
            if (!$active){
                $current=true;
            }
            //для очень большого кол-ва страниц выводим только теущую и две ближашии с лева и с права
            if(($this->currentPage==1 and $i>2)or($this->currentPage==2 and $i>3)){
                $this->buttons[] = new Button('...', $active, '...', $current);
                $i=$pagesCount;
                continue;
            }
            elseif ($this->currentPage>2 and (($i<$this->currentPage-1)or($i>$this->currentPage+1))){
                $this->buttons[] = new Button('...', $active, '...', $current);
                if(($this->currentPage-$i)>2){
                    $i=$this->currentPage-2;
                }
                if($i>$this->currentPage){
                    $i=$pagesCount;
                }
                continue;
            }
            $this->buttons[] = new Button($i, $active, null, $current);
        }
        $this->buttons[] = new Button($this->currentPage + 1, $this->currentPage < $pagesCount, 'Next');
        $this->buttons[] = new Button($pagesCount, $this->currentPage < $pagesCount, 'End');
    }

    //устанавливает позицию в 0 элемент
    public function rewind()
    {
        $this->position = 0;
    }
    //возврат текущей кнопки по текущей позиции
    public function current()
    {
        return $this->buttons[$this->position];
    }
    //текущий ключ
    public function key()
    {
        return $this->position;
    }
    public function next()
    {
        $this->position++;
    }
    public function valid()
    {
        return isset($this->buttons[$this->position]);
    }
}