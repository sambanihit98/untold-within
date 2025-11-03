   <!-- Footer -->
  <footer class="bg-[#4B3B2A] text-[#F5F1EB] py-8 mt-auto">
      <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
          <p>&copy; {{ date('Y') }} Untold Within. All rights reserved.</p>
          <div class="flex gap-4 mt-4 md:mt-0">
              <a href="#" class="hover:text-[#D1BFA3]">Privacy</a>
              <a href="#" class="hover:text-[#D1BFA3]">Terms</a>
              <a href="#about" class="hover:text-[#D1BFA3]">About</a>
          </div>
      </div>
  </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>