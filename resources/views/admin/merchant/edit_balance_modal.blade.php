<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-2" name="name" readonly placeholder="Merchant ID">
                <input type="text" class="form-control mb-2" name="balance" readonly placeholder="Current Balance">
                <select name="balance_type" class="form-control mb-2">
                    <option value="credit">Add</option>
                    <option value="debit">Return</option>
                </select>
                <input type="number" class="form-control mb-2" name="amount" placeholder="Amount" required>
                <textarea name="details" class="form-control mb-2" rows="2" placeholder="Note"></textarea>
                <input type="number" class="form-control mb-2" name="pincode" placeholder="Pin" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="balanceSubmitBtn">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
