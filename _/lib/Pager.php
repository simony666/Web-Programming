<?php

class Pager {
    public $limit;      // Page size
    public $page;       // Current page
    public $item_count; // Total item count
    public $page_count; // Total page count
    public $result;     // Result set (array of records)
    public $count;      // Item count on the current page

    public function __construct($query, $params, $limit, $page) {
        global $db;

        // Set [limit] and [page]
        $this->limit = $limit;
        $this->page = $page;

        // Set [item count]
        $q = preg_replace('/SELECT.+FROM/', 'SELECT COUNT(*) FROM', $query, 1);
        $stm = $db->prepare($q);
        $stm->execute($params);
        $this->item_count = $stm->fetchColumn();


        // Set [page count]
        $this->page_count = ceil($this->item_count / $this->limit);

        // Calculate offset
        $offset = ($this->page - 1) * $this->limit;

        // Set [result]
        $stm = $db->prepare($query . " LIMIT $offset, $this->limit");
        $stm->execute($params);
        $this->result = $stm->fetchAll();

        // Set [count]
        $this->count = count($this->result);
    }

    public function html($href = '', $attr = '') {
        if (!$this->result) return;

        // Generate pager (html)
        $prev = max($this->page - 1, 1);
        $next = min($this->page + 1, $this->page_count);

        echo "<nav class='pager' $attr>";
        echo "<ul class='pagination mt-5'>";
        echo "<li class='page-item'><a href='?page=1&$href' class='page-link'>First</a></li>";
        echo "<li class='page-item'><a href='?page=$prev&$href' class='page-link'>Previous</a></li>";

        foreach (range(1, $this->page_count) as $p) {
            $c = $p == $this->page ? 'active' : '';
            echo "<li class='page-item'><a href='?page=$p&$href' class='$c page-link'>$p</a></li>";
        }

        echo "<li class='page-item'><a href='?page=$next&$href' class='page-link'>Next</a></li>";
        echo "<li class='page-item'><a href='?page=$this->page_count&$href' class='page-link'>Last</a></li>";
        echo "</nav>";
    }
}