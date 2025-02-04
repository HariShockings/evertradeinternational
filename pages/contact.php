
    <style>
.tech{
    position: absolute;
    height: auto;
    left: 0px;
    width: 80px;   
 } 
 section{
    padding: 40px 15%;
 }
 
 .contact{
    background: #e6f5f5;
    height: 100%;
    width: 100%;
    min-height: 100vh;
    display: grid;
    grid-template-columns: repeat(2, 2fr); 
    align-items: center;
    grid-gap: 6rem;
 }
 .contact-img{
     max-width: 100%;
     width:720px;
     height: auto;
     border-radius: 10px;
     box-shadow: 10px 10px 20px #e29578;
    
 }
 
 .contact-form h1{
     font-size: 40px;
     color:#e29578;
     margin-bottom: 20px;  
 }
span{
     color: #e29578;
 }
 .contact-form p{
    color:#222831;
    letter-spacing: 1px;
    line-height: 26px;
    font-size: 1.3 rem;
    margin-bottom: 3.8rem;
    font-weight:bold
 }
.contact-form form{
    position: relative;
}
.contact-form form input, form textarea{
    width: 100%;
    padding: 17px;
    border: none;
    outline: none;
    background: #EEEEEE;
    color: black;
    font-size: 1rem;
    margin-bottom: 0.7rem;
    border-radius: 10px;
    font-weight: bold;
}
.contact-form textarea{
    resize: none;
    height: 200px;
}
.contact-form form .btn{
    display: inline-block;
    background: #e29578;
    font-size: 0.7rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    
    font-weight: 600;
    border: 2px solid transparent;
    border-radius: 10px;
    width: 100px;
    height: 48px;
    transition: ease .20s;
    cursor: pointer;
}
.contact-form form .btn:hover{
    border: 2px solid #e29578;;
    background: transparent;
    transform: scale(1.1);
}
@media (max-width: 1570px){
    section{
        padding: 80px 3%;
        transition: .2s;
    }

.contact-form h1{
    font-size: 60px;
}
.contact-form p{
    margin-bottom: 3rem;
    }
}
@media (max-width: 1090px){
    .Contact{
        grid-gap: 2rem;
        transition: .3s;
    }
}
@media (max-width: 1000px) {
    .Contact{
        grid-template-columns: 1fr;
    }
    .contact-form{
        order: 2;
    }
    .contact-img img{
        max-width: 100%;
        width: 100%;
        height: auto;
        text-align: center;
        margin: 30px;
    }
}







    </style>
        <section class="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="contact-form">
                    <h1>Contact <span>Us</span></h1>
                    <p>Connect with us via phone: 119</p>
                    <form action="">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Name" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Write a subject" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Your Message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact-img">
                    <img src="assets/img/house1.jpg" class="img-fluid" alt="Contact Image">
                </div>
            </div>
        </div>
    </div>
</section>


    <script src="assets/js/switchPage.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar when sidebar toggle button is clicked
            $('.navbar-toggler').on('click', function() {
                $('.sidebar').toggleClass('show');
            });

            // Close sidebar when sidebar close button is clicked
            $('.sidebar-close').on('click', function() {
                $('.sidebar').removeClass('show');
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>