<h1>My Articles</h1>

<?= $this->Html->link('Add Article', ['action' => 'add']) ?>

<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($articles as $article) : ?>
        <tr>
            <td>
                <?= $this->HTML->link($article->title, ['action' => 'view', $article->slug]) ?>
            </td>

            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>

            <td>
                <?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?>
                <span> | </span>
                <?= $this->Form->postLink('Delete', ['action' => 'delete', $article->slug], ['confirm' => 'Are you sure?']) ?>
            </td>
        </tr>

    <?php endforeach; ?>


</table>