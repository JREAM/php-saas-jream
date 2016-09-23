<div class="panel panel-primary purchase-history">
    <div class="panel-heading"><h4><i class="fa fa-history opacity-50" aria-hidden="true"></i> Purchase History</h4></div>
    <div class="panel-body">
        <table class="table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Paid</th>
            <th>Gateway</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        {% for purchase in purchases %}
        <tr>
            <td><a href="{{ url('dashboard/course/index/') }}{{ purchase.getProduct().id }}">{{ purchase.getProduct().title }}</a></td>
            <td><sup>$</sup>{{ purchase.getTransaction().amount }}</td>
            <td>{{ purchase.getTransaction().gateway }}</td>
            <td><?=date('d-m-Y', strtotime($purchase->created_at));?></td>
        </tr>
        {% endfor %}
        </tbody>
        </table>
    </div>
</div>
