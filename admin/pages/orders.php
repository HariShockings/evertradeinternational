<style>
    /* General styling for desktop */
.d-flex {
  display: flex;
  gap: 20px; /* Space between sections */
}

/* Styling for sections to take up equal space */
#manage-product, #manage-product-cat {
  flex: 1;
}

/* Media query for mobile devices */
@media (max-width: 768px) {
  /* Change to column layout on small screens */
  .d-flex {
    flex-direction: column;
  }

  /* Add padding or margin if needed */
  #manage-product, #manage-product-cat {
    margin-bottom: 20px;
  }
}
    .card {
      border: none;
      border-radius: 0.75rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    /* Form container fade in animation */
    .product-form {
      animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    /* Editable input styling similar to category table */
    .editable-input {
      width: 100%;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .editable-input:focus {
      background-color: #fff3cd;
      border-color: #ffc107;
    }
    /* Action button transitions */
    .action-cell button {
      margin-right: 5px;
      transition: background-color 0.3s ease;
    }
    .action-cell button:hover {
      opacity: 0.8;
    }
    /* Image preview styling */
    .image-item img {
      border-radius: 0.5rem;
    }
    .image-item button {
      border-radius: 50%;
    }
  </style>



    <div class="container mt-4">
        <h2>Manage Orders</h2>
        <div class="d-flex">
                    <section id="manage-product">
                    <?php include('sections/manage_orders.php'); ?>
                    </section>
                </div>
    </div>